<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function upload(Request $request, ImageUploadHandler $handler) {
        return $this->success(['url' => $handler->save($request->file('file'), $request->type, $request->phone)['path']]);

    }
}
