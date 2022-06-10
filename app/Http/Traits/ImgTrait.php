<?php
namespace App\Http\Traits;
trait ImgTrait
{
  public function saveImage($photo, $folder)
    {
        $file_extension = $photo->getClientOriginalExtension();
       $filename =time().'.'.$file_extension;
       $path=$folder;
        $photo->move($path, $filename);

        return  $filename;
    }
}
