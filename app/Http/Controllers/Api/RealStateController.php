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

    //============LISTAGEM COM FILTRO=========================
    public function show($id)
    {   
       
        try{
            //busca o imóvel que tenha esse id
            $realState = $this->realState->findOrFail($id);
        
            //retorna esse imovel que tenah o id informado com o statusCode
        return response()->json([
            'data' => [
                'msg' => $realState
            ]
        ], 200);

        } catch (\Exception $e) {
            //sinão eu retorno essa mensagem de erro
            return response()->json(['error' => $e->getMessage()], 401);
        }
        
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
            'data' => [
                'msg' => 'Imóvel cadastrado com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
            //sinão eu retorno essa mensagem de erro
            return response()->json(['error' => $e->getMessage()], 401);
        }
        
    }

    //======================EDITAR===================================
    public function update($id, Request $request)
    {   
        //variavel recebendo tudo que foi enviado
        $data = $request->all();
        try{
            //salva nessa variável o imovel que tenha esse id
            $realState = $this->realState->findOrFail($id);
            //chama o metodo update pra esses novos dados que estão na variável $data
            $realState->update($data);
            //retorno a mensagem de atualizado e o StatusCode
        return response()->json([
            'data' => [
                'msg' => 'Imóvel atualizado com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
            //sinão eu retorno essa mensagem de erro
            return response()->json(['error' => $e->getMessage()], 401);
        }
        
    }

     //======================DELETAR===================================
     public function destroy($id)
     {   
         try{
             //busca esse imovel que tenha esse id
             $realState = $this->realState->findOrFail($id);
             //deleta o imovel que tenha esse id
             $realState->delete($id);

             //retorno a mensagemde imovel removido e statusCode 
         return response()->json([
             'data' => [
                 'msg' => 'Imóvel removido com sucesso!'
             ]
         ], 200);
 
         } catch (\Exception $e) {
             //sinão, eu retorno essa mensagem de erro com StatusCode
             return response()->json(['error' => $e->getMessage()], 401);
         }
         
     }
}
