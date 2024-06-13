<?php
namespace App\Listeners;

use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class HasUploadedImageListener
{
    /**
     * Handle the event.
     *
     * @param  ImageWasUploaded  $event
     * @return void
     */
    public function handle(ImageWasUploaded $event)
    {
        // Do compression image
        $path = $event->path();
        
        try {
            // Compress the image
            $compressedImage = Image::make($path)
                ->encode('jpg', 30); // Adjust quality (0-100)

            // Overwrite the original image with the compressed image
            $compressedImage->save($path, 30);

            // Optionally, you can log a message or perform any additional actions here
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            // Handle any errors that occur during image compression
        }
    }
}