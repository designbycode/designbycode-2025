<?php

namespace App\Livewire\Tools;

use Exception;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Image\Image;

class ImageConverterOld extends Component
{
    use WithFileUploads;

    public $image;

    public ?string $originalFormat = null;

    public ?string $originalSize = null;

    public ?string $originalDimensions = null;

    public string $targetFormat = 'jpg';

    public int $quality = 85;

    public bool $isProcessing = false;

    public int $sepiaValue = 0;

    public int $grayscaleValue = 0;

    public int $pixelateValue = 0;

    public int $blurValue = 0;

    public ?string $convertedImagePath = null;

    public ?string $originalFileName = null;

    protected array $rules = [
        'image' => 'required|image|max:10240', // 10MB max
        'targetFormat' => 'required|in:jpg,jpeg,png,webp,gif,bmp',
        'quality' => 'required|integer|min:1|max:100',
    ];

    protected array $messages = [
        'image.required' => 'Please select an image to convert.',
        'image.image' => 'The file must be a valid image.',
        'image.max' => 'The image size must not exceed 10MB.',
    ];

    public function updatedTargetFormat(): void
    {
        // Reset quality to default when format changes
        if (! in_array($this->targetFormat, ['jpg', 'jpeg', 'webp'])) {
            $this->quality = 85;
        }
    }

    public function updatedQuality(): void
    {
        // Ensure quality is within bounds
        $this->quality = max(1, min(100, $this->quality));
    }

    public function updatedImage(): void
    {
        $this->validate(['image' => 'required|image|max:10240']);

        if ($this->image) {
            $this->analyzeImage();
        }
    }

    public function analyzeImage(): void
    {
        try {
            $tempPath = $this->image->getRealPath();

            // Get image info
            $imageInfo = getimagesize($tempPath);
            if ($imageInfo === false) {
                throw new Exception('Unable to get image information');
            }

            $this->originalDimensions = $imageInfo[0].' × '.$imageInfo[1].' px';
            $this->originalFormat = strtoupper(pathinfo($this->image->getClientOriginalName(), PATHINFO_EXTENSION));
            $this->originalSize = $this->formatBytes($this->image->getSize());
            $this->originalFileName = pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);

            // Reset conversion state
            $this->convertedImagePath = null;
        } catch (Exception $e) {
            $this->addError('image', 'Failed to analyze the image. Please try again.');
        }
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $size = (float) $bytes;
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision).' '.$units[$i];
    }

    public function convertImage(): void
    {
        $this->validate();

        $this->isProcessing = true;

        try {
            // Create unique filename
            $filename = $this->originalFileName.'_converted_'.time().'.'.$this->targetFormat;
            $outputPath = 'converted/'.$filename;

            // Get temporary path
            $tempPath = $this->image->getRealPath();

            // Save to storage
            $fullOutputPath = storage_path('app/public/'.$outputPath);

            // Ensure directory exists
            $directory = dirname($fullOutputPath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Convert image using Spatie Image
            $image = Image::load($tempPath);

            // Apply quality settings for JPEG/WebP
            if (in_array($this->targetFormat, ['jpg', 'jpeg', 'webp'])) {
                $image->quality($this->quality);
            }

            // Apply filters
            if ($this->sepiaValue > 0) {
                $image->sepia();
            }
            if ($this->grayscaleValue > 0) {
                $image->greyscale();
            }
            if ($this->pixelateValue > 0) {
                $image->pixelate($this->pixelateValue);
            }
            if ($this->blurValue > 0) {
                $image->blur($this->blurValue);
            }

            // Save with the target format (the extension in filename determines format)
            $image->save($fullOutputPath);

            $this->convertedImagePath = $outputPath;

        } catch (Exception $e) {
            $this->addError('conversion', 'Failed to convert image: '.$e->getMessage());
        } finally {
            $this->isProcessing = false;
        }
    }

    public function download()
    {
        $fullPath = storage_path('app/public/'.$this->convertedImagePath);

        if (! file_exists($fullPath)) {
            $this->addError('download', 'Converted file not found.');

            return;
        }

        $downloadName = $this->originalFileName.'_converted.'.$this->targetFormat;

        return response()->download($fullPath, $downloadName);
    }

    public function resetConverter(): void
    {
        $this->reset([
            'image',
            'originalFormat',
            'originalSize',
            'originalDimensions',
            'convertedImagePath',
            'originalFileName',
        ]);
        $this->targetFormat = 'jpg';
        $this->quality = 85;
    }

    public function render(): View
    {
        return view('livewire.tools.image-converter');
    }

    private function getMimeType(string $format): string
    {
        return match ($format) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            default => 'application/octet-stream',
        };
    }
}
