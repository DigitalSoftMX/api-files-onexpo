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

    public function updateImageAffiliate(Request $request, $id)
    {
        $rules = [
            'image'=> 'max:1024',
            'name_image' =>'required',
        ];
        $messages = [
            'image.max' => 'El tamaño maximo es 1 mega',
            'name_image.required'=> 'El campo es requerido',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
        return $this->response->errorRes($validator->errors(), null);

        if ($request->hasFile('image')) {
            $customFileName = uniqid() . '_.' . $request->image->extension();
            $request->image->storeAs('public/'.$id, $customFileName);

            $path = public_path('storage/'.$id.'/'.$customFileName);
            // error_log('path: '.$path);
            if (file_exists($path)) {

                //Delete image anterior
                $path_temp = public_path('storage/'.$id.'/'.$request->name_image);
                error_log('path_temp: '.$path_temp);
                $delete = false;
                if (file_exists($path_temp)) {
                    unlink($path_temp);
                    $delete = true;
                }

                $data = [
                    'affiliate' => $id,
                    'image' => $customFileName,
                    'delete' => $delete,
                    'path' => '/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
            return $this->response->errorRes('Error la imagen no existe');
        }
        return $this->response->errorRes('Error al actualizar');
    }

    public function addImageSupplier(Request $request, $id, $product = null)
    {
        $rules = ['image'=> 'max:1024'];
        $messages = ['image.max'=> 'El tamaño maximo es 1 mega'];

        $validator = Validator::make($request->all(), $rules, $messages);
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

    public function updateImageSupplier(Request $request, $id, $product = null)
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
                        'path' => '/'.$id.'/products/'.$customFileName,
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
                    'path' => '/'.$id.'/'.$customFileName,
                ];
                return $this->response->successRes('data',$data);
            }
        }
        return $this->response->errorRes('error al crear imagen');
    }

}
