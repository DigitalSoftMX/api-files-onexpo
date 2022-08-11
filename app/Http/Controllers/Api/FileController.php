<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private $response;

    public function __construct(Responses $res)
    {
        $this->response = $res;
    }

    public function addImageAffiliate(Request $request, $id)
    {
        $rules = ['image'=> 'max:1024'];
        $messages = ['image.max'=> 'El tamaño maximo es 1 mega'];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('image')) {
            // error_log('Nombre imagen: '.$request->image->getClientOriginalName());
            // error_log('Tamaño imagen: '.$request->image->getSize());
            $customFileName = uniqid() . '_.' . $request->image->extension();
            $request->image->storeAs('public/'.$id, $customFileName);

            $path = public_path('storage/'.$id.'/'.$customFileName);
            if (file_exists($path)) {
                $data = [
                    'affiliate' => $id,
                    'image' => $customFileName,
                    'path' => '/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

    public function getImageAffiliate(Request $request)
    {
        $rules = [
            'affiliate' => 'required',
            'name_image' => 'required',
        ];
        $messages = [
            'affiliate.required' => 'El campo es requerido',
            'name_image.required' => 'El nombre de la imagen es requerido',
        ];
        $validator = Validator::make($request->all(),$rules,$messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors());

        $path = public_path('storage/'.$request->affiliate.'/'.$request->name_image);
        if (file_exists($path)) {
            $data = [
                'affiliate' => $request->affiliate,
                'image' => $request->name_image,
                'path' => '/'.$request->affiliate.'/'.$request->name_image,
            ];
            return $this->response->successRes('data',$data);
        }
        return $this->response->errorRes('Error la imagen no existe');

    }

    public function addImageSupplier(Request $request, $id, $product = null)
    {
        $rules = ['image'=> 'max:1024'];
        $messages = ['image.max'=> 'El tamaño maximo es 1 mega'];

        $validator = Validator::make($rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('image')) {
            $customFileName = uniqid() . '_.' . $request->image->extension();
            if ($product != null) {
                $request->image->storeAs('public/'.$id.'/products', $customFileName);
                $data = [
                    'affiliate' => $id,
                    'product' => $product,
                    'image' => $customFileName,
                    'path' => '/'.$id.'/products/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }

            $request->image->storeAs('public/'.$id, $customFileName);
            $path = public_path('storage/'.$id.'/'.$customFileName);
            if (file_exists($path)) {
                $data = [
                    'affiliate' => $id,
                    'image' => $customFileName,
                    'path' => '/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

    public function getImageSupplier(Request $request)
    {
        $rules = [
            'supplier' => 'required',
            'name_image' => 'required',
        ];
        $messages = [
            'supplier.required' => 'El campo es requerido',
            'name_image.required' => 'El nombre de la imagen es requerido',
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails())
            return $this->response->errorRes($validator->errors());

        if ($request->product) {
            $path = public_path('storage/'.$request->supplier.'/products/'.$request->name_image);
            if (file_exists($path)) {
                $data = [
                    'supplier' => $request->supplier,
                    'image' => $request->name_image,
                    'product' => $request->product,
                    'path' => '/'.$request->supplier.'/products/'.$request->name_image,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen del producto existe');
        }

        $path = public_path('storage/'.$request->supplier.'/'.$request->name_image);
        if (file_exists($path)) {
            $data = [
                'supplier' => $request->supplier,
                'image' => $request->name_image,
                'path' => '/'.$request->supplier.'/'.$request->name_image,
            ];
            return $this->response->successRes('data',$data);
        }
        return $this->response->errorRes('Error la imagen no existe');

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
