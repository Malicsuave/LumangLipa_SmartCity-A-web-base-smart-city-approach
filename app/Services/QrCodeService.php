<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    /**
     * Generate a QR code from a string
     *
     * @param string $data The data to encode in the QR code
     * @param int $size Size of the QR code
     * @return string Base64 encoded image data
     */
    public function generateQrCode(string $data, int $size = 300)
    {
        // Create QR code with standard renderer
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new ImagickImageBackEnd('png')
        );
        
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($data);
        
        // Return as base64 encoded string
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }

    /**
     * Save a QR code to file
     *
     * @param string $data The data to encode in the QR code
     * @param string $path The path to save the QR code
     * @param int $size Size of the QR code
     * @return bool Whether the file was saved successfully
     */
    public function saveQrCode(string $data, string $path, int $size = 300)
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new ImagickImageBackEnd('png')
        );
        
        $writer = new Writer($renderer);
        
        // Generate and save the QR code image
        $writer->writeFile($data, $path);
        return true;
    }
}