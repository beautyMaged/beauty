<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Model\BusinessSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

class ImageManager
{
    public static function upload(string $dir, string $format, $image = null)
    {
        if ($image != null) {
            $imageName = Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $imageName, file_get_contents($image));
        } else {
            $imageName = 'def.png';
        }

        return $imageName;
    }

    public static function update(string $dir, $old_image, string $format, $image = null)
    {
        if (Storage::disk('public')->exists($dir . $old_image))
            Storage::disk('public')->delete($dir . $old_image);
        $imageName = ImageManager::upload($dir, $format, $image);
        return $imageName;
    }

    public static function delete($full_path)
    {
        if (Storage::disk('public')->exists($full_path))
            Storage::disk('public')->delete($full_path);

        return [
            'success' => 1,
            'message' => 'Removed successfully !'
        ];

    }
    
    public function addLogo(UploadedFile $image)
    {
        $originalImage = Image::make($image);

        // Get the logo from the database

        $logo_path = BusinessSetting::where('type', 'company_web_logo')->first()->value;
        $logoUrl = storage_path('app/public/' . $logo_path);
        $logo = Image::make($logoUrl);

        // Ensure a valid logo is found
        if (!$logo) {
            return $originalImage;
        }

        // Calculate the relative size of the logo 
        $relativeLogoWidth = $originalImage->width() * 0.2; // Adjust the percentage as needed
        $relativeLogoHeight = $relativeLogoWidth * ($logo->height() / $logo->width());

        $logo->resize($relativeLogoWidth, $relativeLogoHeight);

        $logo->opacity(70); // Adjust the opacity as needed


        // Add the logo
        $originalImage->insert($logo, 'top-left', 10, 10);

        $encodedImage = $originalImage->encode($image->extension());

        return $encodedImage;
    }
}
