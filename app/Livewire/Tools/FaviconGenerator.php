<?php

namespace App\Livewire\Tools;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Image;

class FaviconGenerator extends Component
{
    use WithFileUploads;

    public $image;
    public $convertedImages = [];

    protected $rules = [
        'image' => 'required|image|max:2048', // 2MB Max
    ];

    public function convertImage()
    {
        $this->validate();

        $sizes = [
            ['name' => 'favicon-16x16.png', 'width' => 16, 'height' => 16],
            ['name' => 'favicon-32x32.png', 'width' => 32, 'height' => 32],
            ['name' => 'apple-touch-icon.png', 'width' => 180, 'height' => 180],
        ];

        $converted = [];

        foreach ($sizes as $size) {
            $filename = uniqid() . '-' . $size['name'];
            $path = 'favicons/' . $filename;

            // Save original uploaded image to temp location
            $tmpPath = $this->image->storeAs('favicons/tmp', $filename, 'public');

            // Use Spatie\Image to resize
            Image::load(Storage::disk('public')->path($tmpPath))
                ->width($size['width'])
                ->height($size['height'])
                ->save(Storage::disk('public')->path($path));

            // Remove tmp
            Storage::disk('public')->delete($tmpPath);

            $converted[] = [
                'name' => $size['name'],
                'url' => Storage::url($path),
                'size' => "{$size['width']}x{$size['height']}",
            ];
        }

        // ICO generation (optional, can use intervention/image or a package for real ICOs)
        $icoFilename = uniqid() . '-favicon.ico';
        $icoPath = 'favicons/' . $icoFilename;
        // For demonstration, just copy 32x32 PNG as .ico (replace with real ICO generation)
        Storage::disk('public')->copy('favicons/' . $converted[1]['name'], $icoPath);

        $converted[] = [
            'name' => 'favicon.ico',
            'url' => Storage::url($icoPath),
            'size' => '32x32 (ico)',
        ];

        $this->convertedImages = $converted;
    }

    public function download($url)
    {
        // This could be implemented with a route that returns a download response
        // Or you can use <a href="..." download> in the view for direct download
    }

    public function removeImage()
    {
        $this->image = null;
        $this->convertedImages = [];
    }


    public function render()
    {
        return view('livewire.tools.favicon-generator');
    }
}
