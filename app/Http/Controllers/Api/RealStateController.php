<?php

namespace App\Http\Controllers\Api;
use App\RealState;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealStateController extends Controller
{
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    //===================LISTAR==============================
    public function index()
    {
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);
    }

    //==============SALVAR====================================
    public function store(Request $request)
    {   
        //variavel recebendo tudo que foi enviado
        $data = $request->all();
        try{
            //salvo o que foi digitado
            $realState = $this->realState->create($data);
            //retorno o que foi salvo
        return response()->json([
            'data' => 'Imóvel cadastrado com sucesso!'
        ], 200);

        } catch (\Exception $e) {
            //sinão eu retorno essa mensagem de erro
            return response()->json(['error' => $e->getMessage()], 401);
        }
        
    }
}
