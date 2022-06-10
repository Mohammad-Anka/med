<?php

namespace App\Http\Traits;

trait ApiTrait
{
  public function returnJson($data, $status = 200, $msg = '')
  {
    return response()->json(['data' => $data, 'status' => $status, 'msg' => $msg]);
  }

  //represent when the object has image
  public function editHasImage($obj,$folder)
  {   $host = request()->getHttpHost();
    if($obj->isImage){
      $obj ->image= $host.'/'. 'images/'.$folder.'/'.$obj ->image;
    }

    return $obj;
  }
}
