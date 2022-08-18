<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    private $response;

    public function __construct(Responses $res)
    {
        $this->response = $res;
    }

    public function addImage(Request $request, $id)
    {
        $rules = [
            'image' => 'required'
        ];
        $messages = [
            'image.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        $path = public_path('storage/'.$id);
        //Crear directorio si no exite
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $data = explode( ',', $request->image);
        if (count($data) == 1) {
            return $this->response->errorRes('Data incorrecta');
        }
        $temp = explode('/', $data[0]);
        if (count($data) == 1) {
            return $this->response->errorRes('Temp incorrecto');
        }
        $extension = explode(';', $temp[1]);
        if (count($extension) == 1) {
            return $this->response->errorRes('Extension incorrecto');
        }

        $base = base64_decode( $data[ 1 ] );
        $customFileName = uniqid() . '_.' . $extension[0];

        $status = file_put_contents($path.'/'.$customFileName,$base);

        if($status){
            $path = public_path('storage/'.$id.'/'.$customFileName);
            if (file_exists($path)) {
                $data = [
                    'user' => $id,
                    'image' => $customFileName,
                    'path' => 'storage/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

    public function getImageUser(Request $request)
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
                'path' => 'storage/'.$request->affiliate.'/'.$request->name_image,
            ];
            return $this->response->successRes('data',$data);
        }
        return $this->response->errorRes('Error la imagen no existe');

    }

    public function updateImageUser(Request $request, $id)
    {
        $rules = [
            'image' => 'required',
            'name_image' =>'required'
        ];
        $messages = [
            'image.required'=> 'El campo es requerido',
            'name_image.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        //Crear directorio si no exite
        $path = public_path('storage/'.$id);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $data = explode( ',', $request->image);
        if (count($data) == 1) {
            return $this->response->errorRes('Data incorrecta');
        }

        $temp = explode('/', $data[0]);
        if (count($data) == 1) {
            return $this->response->errorRes('Temp incorrecto');
        }
        $extension = explode(';', $temp[1]);
        if (count($extension) == 1) {
            return $this->response->errorRes('Extension incorrecto');
        }


        $base = base64_decode( $data[ 1 ] );
        $customFileName = uniqid() . '_.' . $extension[0];

        $status = file_put_contents($path.'/'.$customFileName,$base);

        if($status){
            $path = public_path('storage/'.$id.'/'.$customFileName);
            if (file_exists($path)) {
                //Delete image anterior
                $path_temp = public_path('storage/'.$id.'/'.$request->name_image);
                // error_log('path_temp: '.$path_temp);
                $delete = false;
                if (file_exists($path_temp)) {
                    unlink($path_temp);
                    $delete = true;
                }

                $data = [
                    'user' => $id,
                    'image' => $customFileName,
                    'delete' => $delete,
                    'path' => 'storage/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen no existe');
        }
        return $this->response->errorRes('Error al crear imagen');
    }

    public function addImageProduct(Request $request, $id, $product = null)
    {
        $rules = [
            'image' => 'required'
        ];
        $messages = [
            'image.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($product == null) {
            return $this->response->errorRes('Falta parametro del producto');
        }

        //Crear directorio de $id User si no existe
        $path = public_path('storage/'.$id);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        //Crear directorio Products si no exite
        $path = public_path('storage/'.$id.'/products');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $data = explode( ',', $request->image);
        if (count($data) == 1) {
            return $this->response->errorRes('Data incorrecta');
        }
        $temp = explode('/', $data[0]);
        if (count($data) == 1) {
            return $this->response->errorRes('Temp incorrecto');
        }
        $extension = explode(';', $temp[1]);
        if (count($extension) == 1) {
            return $this->response->errorRes('Extension incorrecto');
        }

        $base = base64_decode( $data[ 1 ] );
        $customFileName = uniqid() . '_.' . $extension[0];

        $status = file_put_contents($path.'/'.$customFileName,$base);

        if($status){
            $path = public_path('storage/'.$id.'/products/'.$customFileName);
            if (file_exists($path)) {
                $data = [
                    'user' => $id,
                    'image' => $customFileName,
                    'path' => 'storage/'.$id.'/products/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

    /* NOTE: Continuar aqui */
    public function getImageProduct(Request $request)
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
                    'path' => 'storage/'.$request->supplier.'/products/'.$request->name_image,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen del producto existe');
        }
        return $this->response->errorRes('Error la imagen no existe');
    }

    public function updateImageProduct(Request $request, $id, $product = null)
    {
        $rules = [
            'image' => 'required',
            'name_image' =>'required'
        ];
        $messages = [
            'image.required'=> 'El campo es requerido',
            'name_image.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        //Crear directorio de $id User si no existe
        $path = public_path('storage/'.$id);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        //Crear directorio Products si no exite
        $path = public_path('storage/'.$id.'/products');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $data = explode( ',', $request->image);
        if (count($data) == 1) {
            return $this->response->errorRes('Data incorrecta');
        }
        $temp = explode('/', $data[0]);
        if (count($data) == 1) {
            return $this->response->errorRes('Temp incorrecto');
        }
        $extension = explode(';', $temp[1]);
        if (count($extension) == 1) {
            return $this->response->errorRes('Extension incorrecto');
        }

        $base = base64_decode( $data[ 1 ] );
        $customFileName = uniqid() . '_.' . $extension[0];

        $status = file_put_contents($path.'/'.$customFileName,$base);

        if($status){
            $path = public_path('storage/'.$id.'/products/'.$customFileName);
            if (file_exists($path)) {
                //Delete image anterior
                $path_temp = public_path('storage/'.$id.'/products/'.$request->name_image);
                $delete = false;
                if (file_exists($path_temp)) {
                    unlink($path_temp);
                    $delete = true;
                }
                $data = [
                    'user' => $id,
                    'image' => $customFileName,
                    'delete' => $delete,
                    'path' => 'storage/'.$id.'/products/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

}
