<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private $response;

    public function __construct(Responses $res){
        $this->response = $res;
    }

    public function addimage(Request $request,$id)
    {
        $rules = ['image'=> 'max:1024'];
        $messages = ['image.max'=> 'El tamaño maximo es 1 mega'];

        $validator = Validator::make($rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('image')) {
            error_log('Nombre imagen: '.$request->image->getClientOriginalName());
            error_log('Tamaño imagen: '.$request->image->getSize());
            $customFileName = uniqid() . '_.' . $request->image->extension();
            $request->image->storeAs('public/'.$id, $customFileName);
        }

        $data = [
            'id' => $id,
            'image' => $customFileName,
        ];
        return $this->response->successRes('data', $data);
        // return response()->json(['ok'=>true,'data'=>$data]);
    }

    public function addfile(Request $request,$id)
    {
        if ($request->hasFile('photo')) {
            $image      = $request->file('photo');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            // $img->resize(120, 120, function ($constraint) {
            //     $constraint->aspectRatio();
            // });

            $img->stream(); // <-- Key point

            //dd();
            Storage::disk('local')->put('images/1/smalls'.'/'.$fileName, $img, 'public');

            $data = [
                'id' => $id,
                'image' => $fileName,
            ];
        }
        return response()->json(['ok'=>true,'data'=>$data]);
    }
}
