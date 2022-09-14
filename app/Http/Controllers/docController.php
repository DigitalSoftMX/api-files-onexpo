<?php

namespace App\Http\Controllers;

use App\Repositories\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class docController extends Controller
{
    private $response;

    public function __construct(Responses $res)
    {
        $this->response = $res;
    }

    public function get(Request $request)
    {
        $rules = [
            'user' => 'required',
            'name_file' => 'required',
        ];
        $messages = [
            'user.required' => 'El campo es requerido',
            'name_file.required' => 'El nombre de la imagen es requerido',
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails())
            return $this->response->errorRes($validator->errors());

        // Ontener extension de archivo pdf o xml
        $data = explode( '.', $request->name_file);
        if (count($data) == 1) {
            return $this->response->errorRes('Data incorrecta');
        }

        if ($data[1] == 'pdf') {
            $path = public_path('storage/'.$request->user.'/pdfs/'.$request->name_file);
            if (file_exists($path)) {
                $data = [
                    'user' => $request->user,
                    'doc' => $request->name_file,
                    'path' => 'storage/'.$request->user.'/pdfs/'.$request->name_file,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen del producto existe');
        }

        if ($data[1] == 'xml') {
            $path = public_path('storage/'.$request->user.'/xmls/'.$request->name_file);
            if (file_exists($path)) {
                $data = [
                    'user' => $request->user,
                    'doc' => $request->name_file,
                    'path' => 'storage/'.$request->user.'/xmls/'.$request->name_file,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen del producto existe');
        }
        return $this->response->errorRes('Error la imagen no existe');
    }

    public function add(Request $request, $id)
    {
        $rules = [
            'doc' => 'required'
        ];
        $messages = [
            'doc.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        $data = explode( ',', $request->doc);
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

        if ($extension[0] == 'pdf') {
            //Crear directorio de $id User si no existe
            $path = public_path('storage/'.$id);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            //Crear directorio Products si no exite
            $path = public_path('storage/'.$id.'/pdfs');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $status = file_put_contents($path.'/'.$customFileName,$base);

            if($status){
                $path = public_path('storage/'.$id.'/pdfs/'.$customFileName);
                if (file_exists($path)) {
                    $data = [
                        'user' => $id,
                        'image' => $customFileName,
                        'path' => 'storage/'.$id.'/pdfs/'.$customFileName,
                    ];
                    return $this->response->successRes('data',$data);
                }
            }
        }
        if ($extension[0] == 'xml') {
            //Crear directorio de $id User si no existe
            $path = public_path('storage/'.$id);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            //Crear directorio xml si no exite
            $path = public_path('storage/'.$id.'/xmls');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $status = file_put_contents($path.'/'.$customFileName,$base);

            if($status){
                $path = public_path('storage/'.$id.'/xmls/'.$customFileName);
                if (file_exists($path)) {
                    $data = [
                        'user' => $id,
                        'image' => $customFileName,
                        'path' => 'storage/'.$id.'/xmls/'.$customFileName,
                    ];
                    return $this->response->successRes('data',$data);
                }
            }
        }
        return $this->response->errorRes('error al crear archivo');
    }

    public function update(Request $request, $id, $product = null)
    {
        $rules = [
            'doc' => 'required',
            'name_file' =>'required'
        ];
        $messages = [
            'doc.required'=> 'El campo es requerido',
            'name_file.required'=> 'El campo es requerido'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        $data = explode( ',', $request->doc);
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

        if ($extension[0] == 'pdf') {
            //Crear directorio de $id User si no existe
            $path = public_path('storage/'.$id);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            //Crear directorio Products si no exite
            $path = public_path('storage/'.$id.'/pdfs');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $status = file_put_contents($path.'/'.$customFileName,$base);

            if($status){
                $path = public_path('storage/'.$id.'/pdfs/'.$customFileName);
                if (file_exists($path)) {
                    //Delete image anterior
                    $path_temp = public_path('storage/'.$id.'/pdfs/'.$request->name_file);
                    $delete = false;
                    if (file_exists($path_temp)) {
                        unlink($path_temp);
                        $delete = true;
                    }
                    $data = [
                        'user' => $id,
                        'image' => $customFileName,
                        'delete' => $delete,
                        'path' => 'storage/'.$id.'/pdfs/'.$customFileName,
                    ];
                    return $this->response->successRes('data',$data);
                }
            }
        }
        if ($extension[0] == 'xml') {
            //Crear directorio de $id User si no existe
            $path = public_path('storage/'.$id);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            //Crear directorio xml si no exite
            $path = public_path('storage/'.$id.'/xmls');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $status = file_put_contents($path.'/'.$customFileName,$base);

            if($status){
                $path = public_path('storage/'.$id.'/xmls/'.$customFileName);
                if (file_exists($path)) {
                    //Delete image anterior
                    $path_temp = public_path('storage/'.$id.'/xmls/'.$request->name_file);
                    $delete = false;
                    if (file_exists($path_temp)) {
                        unlink($path_temp);
                        $delete = true;
                    }
                    $data = [
                        'user' => $id,
                        'image' => $customFileName,
                        'delete' => $delete,
                        'path' => 'storage/'.$id.'/xmls/'.$customFileName,
                    ];
                    return $this->response->successRes('data',$data);
                }
            }
        }

        return $this->response->errorRes('error al crear imagen');
    }
}
