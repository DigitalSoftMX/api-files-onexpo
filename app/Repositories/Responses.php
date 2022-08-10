<?php

namespace App\Repositories;

class Responses
{
    // Funcion mensaje correcto
    public function successRes($name, $data)
    {
        return response()->json(['ok' => true, $name => $data]);
    }
    // Funcion mensajes de error
    public function errorRes($message)
    {
        return response()->json(['ok' => false, 'message' => $message]);
    }
}
