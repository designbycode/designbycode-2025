<?php

namespace App\Livewire\Tools;

use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class FaviconGenerator extends Component
{
    use WithFileUploads;

    public $photo;
    public $feedbackMessage = '';

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:10240', // 10MB Max, types: png, jpg, webp
        ], [
            'photo.image' => 'The uploaded file must be an image (png, jpg, webp).',
            'photo.max' => 'The image may not be greater than 10MB.',
        ]);

        $this->feedbackMessage = 'Image uploaded. Ready to generate favicons.';
        $this->cleanupOldFaviconData();
        session()->forget('favicon_generation_complete');
    }

    private function cleanupOldFaviconData()
    {
        $tempDirName = session('favicon_temp_dir_name');
        if ($tempDirName) {
            $path = storage_path('app/' . $tempDirName);
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
            }
        }
        session()->forget([
            'favicon_generated_files',
            'favicon_temp_dir_name',
            'favicon_generation_complete',
        ]);
    }

    public function generateFavicons()
    {
        $this->validate([
            'photo' => 'required|image|max:10240',
        ], [
            'photo.required' => 'Please upload an image first.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.max' => 'The image may not be greater than 10MB.',
        ]);

        $this->cleanupOldFaviconData();
        $this->feedbackMessage = 'Generating favicons... please wait.';

        $generatedFilesLocal = [];
        $imagePath = $this->photo->getRealPath();

        $outputSizes = [
            'android-chrome-192x192.png' => ['width' => 192, 'height' => 192, 'format' => 'png'],
            'android-chrome-512x512.png' => ['width' => 512, 'height' => 512, 'format' => 'png'],
            'apple-touch-icon.png'       => ['width' => 180, 'height' => 180, 'format' => 'png'],
            'favicon-16x16.png'          => ['width' => 16, 'height' => 16, 'format' => 'png'],
            'favicon-32x32.png'          => ['width' => 32, 'height' => 32, 'format' => 'png'],
        ];

        $tempDirNameLocal = 'favicons_tool_' . Str::random(20); // Unique prefix
        $tempPath = storage_path('app/' . $tempDirNameLocal);

        if (!File::isDirectory($tempPath)) {
            File::makeDirectory($tempPath, 0755, true, true);
        }

        try {
            // Generate PNG images
            foreach ($outputSizes as $filename => $dimensions) {
                $img = Image::make($imagePath)
                    ->resize($dimensions['width'], $dimensions['height'], function ($constraint) {
                        $constraint->aspectRatio(); // Maintain aspect ratio
                        $constraint->upsize(); // Prevent upsizing
                    })->encode($dimensions['format']);

                $filePath = $tempPath . '/' . $filename;
                $img->save($filePath);
                $generatedFilesLocal[$filename] = $filePath;
            }

            // Generate favicon.ico from favicon-32x32.png
            if (isset($generatedFilesLocal['favicon-32x32.png'])) {
                $icoFilename = 'favicon.ico';
                $icoFilePath = $tempPath . '/' . $icoFilename;
                Image::make($generatedFilesLocal['favicon-32x32.png'])->encode('ico')->save($icoFilePath);
                $generatedFilesLocal[$icoFilename] = $icoFilePath;
            } else {
                // Fallback or error if 32x32 couldn't be made - though loop should ensure it or fail earlier
            }

            // Create site.webmanifest
            $manifestContent = json_encode([
                "name" => "",
                "short_name" => "",
                "icons" => [
                    ["src" => "/android-chrome-192x192.png", "sizes" => "192x192", "type" => "image/png"],
                    ["src" => "/android-chrome-512x512.png", "sizes" => "512x512", "type" => "image/png"]
                ],
                "theme_color" => "#ffffff",
                "background_color" => "#ffffff",
                "display" => "standalone"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            $manifestFilePath = $tempPath . '/site.webmanifest';
            File::put($manifestFilePath, $manifestContent);
            $generatedFilesLocal['site.webmanifest'] = $manifestFilePath;

            // Create README.md
            $readmeContent = <<<HTML
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
            $readmeFilePath = $tempPath . '/README.md';
            File::put($readmeFilePath, $readmeContent);
            $generatedFilesLocal['README.md'] = $readmeFilePath;

            session([
                'favicon_generated_files' => $generatedFilesLocal,
                'favicon_temp_dir_name' => $tempDirNameLocal,
                'favicon_generation_complete' => true
            ]);

            $this->feedbackMessage = 'Favicons generated successfully! Click Download to get your ZIP.';
            $this->dispatchBrowserEvent('generationComplete');

        } catch (\Exception $e) {
            $this->feedbackMessage = 'Error during favicon generation: ' . $e->getMessage();
            // Clean up temp directory on error
            if (File::isDirectory($tempPath)) {
                File::deleteDirectory($tempPath);
            }
            session()->forget(['favicon_generated_files', 'favicon_temp_dir_name', 'favicon_generation_complete']);
            return;
        }
    }

    public function downloadFaviconZip()
    {
        if (!session('favicon_generation_complete', false)) {
            $this->feedbackMessage = 'No files available for download or session expired. Please generate favicons again.';
            return null;
        }

        $generatedFiles = session('favicon_generated_files');
        $tempDirName = session('favicon_temp_dir_name');

        if (!$generatedFiles || !$tempDirName) {
             $this->feedbackMessage = 'Error: Temporary file data not found. Please try generating again.';
             $this->cleanupOldFaviconData();
             return null;
        }

        $tempPath = storage_path('app/' . $tempDirName);
        if (!File::isDirectory($tempPath)) {
            $this->feedbackMessage = 'Error: Temporary directory not found. Please try generating again.';
            $this->cleanupOldFaviconData();
            return null;
        }

        $zip = new ZipArchive;
        $zipFileName = 'favicons_bundle.zip';
        $zipFilePath = $tempPath . '/' . $zipFileName;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($generatedFiles as $nameInZip => $diskPath) {
                if (File::exists($diskPath)) {
                    $zip->addFile($diskPath, $nameInZip);
                }
            }
            $zip->close();

            if (!File::exists($zipFilePath)) {
                $this->feedbackMessage = 'Failed to create ZIP file on disk.';
                $this->cleanupOldFaviconData();
                return null;
            }
        } else {
            $this->feedbackMessage = 'Failed to open/create ZIP file.';
            $this->cleanupOldFaviconData();
            return null;
        }

        // Ensure cleanup runs after download response is sent.
        register_shutdown_function(function () {
            $this->cleanupOldFaviconData();
        });

        return response()->download($zipFilePath, $zipFileName);
    }

    public function render()
    {
        return view('livewire.tools.favicon-generator');
    }
}
