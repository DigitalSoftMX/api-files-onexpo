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

        $path = public_path('storage/'.$request->supplier.'/'.$request->name_image);
        if (file_exists($path)) {
            $data = [
                'supplier' => $request->supplier,
                'image' => $request->name_image,
                'path' => 'storage/'.$request->supplier.'/'.$request->name_image,
            ];
            return $this->response->successRes('data',$data);
        }
        return $this->response->errorRes('Error la imagen no existe');

    }

    public function add(Request $request, $id)
    {
        $rules = [
            'doc' => 'max:1024',
            'type' => 'required',
        ];
        $messages = [
            'doc.max'=> 'El tamaño maximo es 1 mega',
            'type.required' => 'El campo es requerido',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('doc')) {
            $customFileName = uniqid() . '_.' . $request->doc->extension();

            if ($request->type == 'pdf') {
                $request->doc->storeAs('public/'.$id.'/pdf', $customFileName);
                $data = [
                    'user' => $id,
                    'doc' => $customFileName,
                    'path' => 'storage/'.$id.'/pdf/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }

            if ($request->type == 'xml') {
                $request->doc->storeAs('public/'.$id.'/xml', $customFileName);
                $data = [
                    'user' => $id,
                    'doc' => $customFileName,
                    'path' => 'storage/'.$id.'/xml/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }

            return $this->response->errorRes('Error type no valido');
        }
        return $this->response->errorRes('error al crear imagen');
    }

    public function update(Request $request, $id, $product = null)
    {
        $rules = [
            'image'=> 'max:1024',
            'name_image' =>'required|min:10',
        ];
        $messages = [
            'image.max' => 'El tamaño maximo es 1 mega',
            'name_image.required'=> 'El campo es requerido',
            'name_image.min'=> 'El nombre es de mas de 10 caracteres',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('image')) {
            $customFileName = uniqid() . '_.' . $request->image->extension();
            //Carpeta productos
            if ($product != null) {
                //Guardar imagen nueva
                $request->image->storeAs('public/'.$id.'/products', $customFileName);
                $path = public_path('storage/'.$id.'/products/'.$customFileName);
                // dd($path);
                if (file_exists($path)) {
                    //Delete image anterior
                    $path_temp = public_path('storage/'.$id.'/products/'.$request->name_image);
                    $delete = false;
                    if (file_exists($path_temp)) {
                        unlink($path_temp);
                        $delete = true;
                    }
                    //Data a retornar
                    $data = [
                        'affiliate' => $id,
                        'product' => $product,
                        'image' => $customFileName,
                        'delete' => $delete,
                        'path' => 'storage/'.$id.'/products/'.$customFileName,
                    ];
                    return $this->response->successRes('data',$data);
                }
                return $this->response->errorRes('Error al crear imagen');
            }
            //Guaradar imagen nueva
            $request->image->storeAs('public/'.$id, $customFileName);
            $path = public_path('storage/'.$id.'/'.$customFileName);
            // dd($path);
            if (file_exists($path)) {
                //Delete image anterior
                $path_temp = public_path('storage/'.$id.'/'.$request->name_image);
                $delete = false;
                if (file_exists($path_temp)) {
                    unlink($path_temp);
                    $delete = true;
                }
                //Data a retornar
                $data = [
                    'affiliate' => $id,
                    'image' => $customFileName,
                    'delete' => $delete,
                    'path' => 'storage/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }
}
