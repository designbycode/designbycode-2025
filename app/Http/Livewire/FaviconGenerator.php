<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Assuming Intervention Image is installed and aliased
use Intervention\Image\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads; // For streamDownload and clean up
use ZipArchive; // For zipping files

class FaviconGenerator extends Component
{
    use WithFileUploads;

    public $photo;

    public $feedbackMessage = '';
    // public $generatedFiles = []; // Replaced by session storage
    // public $tempDirName; // Replaced by session storage

    public function mount()
    {
        // Clean up any old data on component mount, in case of browser refresh or navigating away and back.
        // $this->cleanupOldFaviconData(); // Decided against aggressive cleanup on mount, will do on new action.
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:10240', // 10MB Max
        ]);
        $this->feedbackMessage = 'Image uploaded successfully. Ready to generate favicons.';
        $this->cleanupOldFaviconData(); // Clean up any previous generation attempt data
        session()->forget('favicon_generation_complete'); // Reset generation status
    }

    private function cleanupOldFaviconData()
    {
        $tempDirName = session('favicon_temp_dir_name');
        if ($tempDirName) {
            $path = storage_path('app/'.$tempDirName);
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            }
        }
        session()->forget([
            'favicon_generated_files',
            'favicon_temp_dir_name',
            'favicon_generation_complete',
            'favicon_zip_path',
        ]);
    }

    public function generateFavicons()
    {
        $this->validate([
            'photo' => 'required|image|max:10240',
        ]);

        $this->cleanupOldFaviconData(); // Ensure clean state before generation
        $this->feedbackMessage = 'Generating favicons...';

        $generatedFilesLocal = []; // Local array for this method's scope
        $imagePath = $this->photo->getRealPath();

        $outputSizes = [
            'android-chrome-192x192.png' => ['width' => 192, 'height' => 192, 'format' => 'png'],
            'android-chrome-512x512.png' => ['width' => 512, 'height' => 512, 'format' => 'png'],
            'apple-touch-icon.png' => ['width' => 180, 'height' => 180, 'format' => 'png'],
            'favicon-16x16.png' => ['width' => 16, 'height' => 16, 'format' => 'png'],
            'favicon-32x32.png' => ['width' => 32, 'height' => 32, 'format' => 'png'],
        ];

        $tempDirNameLocal = 'favicons_temp_'.Str::random(16);
        $tempPath = storage_path('app/'.$tempDirNameLocal);

        if (! File::isDirectory($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }

        try {
            foreach ($outputSizes as $filename => $dimensions) {
                $image = Image::make($imagePath)
                    ->resize($dimensions['width'], $dimensions['height'])
                    ->encode($dimensions['format']);
                $filePath = $tempPath.'/'.$filename;
                $image->save($filePath);
                $generatedFilesLocal[$filename] = $filePath;
            }

            if (isset($generatedFilesLocal['favicon-32x32.png'])) {
                $icoFilename = 'favicon.ico';
                $icoFilePath = $tempPath.'/'.$icoFilename;
                Image::make($generatedFilesLocal['favicon-32x32.png'])->encode('ico')->save($icoFilePath);
                $generatedFilesLocal[$icoFilename] = $icoFilePath;
            }

            $manifestContent = json_encode([
                'name' => '', 'short_name' => '',
                'icons' => [
                    ['src' => '/android-chrome-192x192.png', 'sizes' => '192x192', 'type' => 'image/png'],
                    ['src' => '/android-chrome-512x512.png', 'sizes' => '512x512', 'type' => 'image/png'],
                ],
                'theme_color' => '#ffffff', 'background_color' => '#ffffff', 'display' => 'standalone',
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $manifestFilePath = $tempPath.'/site.webmanifest';
            File::put($manifestFilePath, $manifestContent);
            $generatedFilesLocal['site.webmanifest'] = $manifestFilePath;

            $readmeContent = <<<'HTML'
To add these favicons to your HTML, insert the following lines into the `<head>` section of your HTML document:

```html
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="shortcut icon" href="/favicon.ico">
```

Make sure these files are placed in the root of your website or adjust the `href` paths accordingly.
HTML;
            $readmeFilePath = $tempPath.'/README.md';
            File::put($readmeFilePath, $readmeContent);
            $generatedFilesLocal['README.md'] = $readmeFilePath;

            // Store data in session for download method
            session([
                'favicon_generated_files' => $generatedFilesLocal,
                'favicon_temp_dir_name' => $tempDirNameLocal,
                'favicon_generation_complete' => true,
            ]);

            $this->feedbackMessage = 'All files generated successfully! Ready to download.';
            session()->flash('message', 'Generation complete. Click Download button.');
            $this->dispatchBrowserEvent('generationComplete'); // For frontend to enable download button or auto-trigger

        } catch (\Exception $e) {
            $this->feedbackMessage = 'Error during file generation: '.$e->getMessage();
            // Clean up temp directory on error
            if (File::isDirectory($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            // Clear any partial session data
            session()->forget(['favicon_generated_files', 'favicon_temp_dir_name', 'favicon_generation_complete']);

            return;
        }
    }

    public function downloadFaviconZip()
    {
        if (! session('favicon_generation_complete', false)) {
            $this->feedbackMessage = 'No files available for download or session expired. Please generate favicons first.';
            session()->flash('error', 'No files available for download or session expired.'); // More prominent error

            return null;
        }

        $generatedFiles = session('favicon_generated_files');
        $tempDirName = session('favicon_temp_dir_name');

        if (! $generatedFiles || ! $tempDirName) {
            $this->feedbackMessage = 'Error: Temporary file data not found. Please try generating again.';
            session()->flash('error', 'Temporary file data not found.');
            $this->cleanupOldFaviconData(); // Clean up inconsistent state

            return null;
        }

        $tempPath = storage_path('app/'.$tempDirName);
        if (! File::isDirectory($tempPath)) {
            $this->feedbackMessage = 'Error: Temporary directory not found. Please try generating again.';
            session()->flash('error', 'Temporary directory not found.');
            $this->cleanupOldFaviconData();

            return null;
        }

        $zip = new ZipArchive;
        $zipFileName = 'favicons.zip';
        $zipFilePath = $tempPath.'/'.$zipFileName; // Store ZIP inside the temp dir

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($generatedFiles as $nameInZip => $diskPath) {
                if (File::exists($diskPath)) {
                    $zip->addFile($diskPath, $nameInZip);
                } else {
                    // Log this error or notify user, some files are missing
                    // For now, skip missing files to allow zip creation if possible
                }
            }
            $zip->close();

            // At this point, $zipFilePath should exist
            if (! File::exists($zipFilePath)) {
                $this->feedbackMessage = 'Failed to create ZIP file on disk.';
                session()->flash('error', 'Failed to create ZIP file (not found after creation).');
                $this->cleanupOldFaviconData(); // Clean up

                return null;
            }

        } else {
            $this->feedbackMessage = 'Failed to open/create ZIP file.';
            session()->flash('error', 'Failed to open/create ZIP file.');
            $this->cleanupOldFaviconData(); // Clean up

            return null;
        }

        // After download, the entire temp directory (including the zip) will be cleaned up.
        // Use register_shutdown_function for robust cleanup.
        register_shutdown_function([$this, 'cleanupOldFaviconData']);

        return response()->download($zipFilePath, $zipFileName);
        // deleteFileAfterSend(true) would delete the zip, but cleanupOldFaviconData will get the whole dir.
    }

    public function render()
    {
        return view('livewire.favicon-generator');
    }
}
