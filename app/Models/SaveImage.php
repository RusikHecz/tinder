<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManagerStatic as Image;

class SaveImage extends Model
{

    static function sv($image){
        $thumbnailImage = Image::make($image);
        $thumbnailPath = public_path() . '/upload/thumbnails/';
        $originalPath = public_path() . '/upload/images/';
        $webpPath = public_path() . '/upload/webp/';
        $filenamewithextension = $image->getClientOriginalName();
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        $extension = $image->getClientOriginalExtension();
        $filename_format = time() . $image->getClientOriginalName();
        $thumbnailImage->save($originalPath . $filenamewithextension);

        //500x500 image save
        $thumbnailImage->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailImage->save($thumbnailPath . '500x500/' . $filenamewithextension);

        $webp_Image = Image::make($image);
        $webp_Image->encode('webp', 90);

        $webp_Image->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $webp_Image->save($webpPath . '500x500/' . $filename . '.webp');

        // 1000width image save
        $thumbnailImage->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailImage->save($thumbnailPath . '1000width/' . $filenamewithextension);

        $webp_Image = Image::make($image);
        $webp_Image->encode('webp', 90);

        $webp_Image->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $webp_Image->save($webpPath . '1000width/' . $filename . '.webp');

        return $filenamewithextension;
    }
}
