<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\RealStateRequest;
use App\RealState;
use App\Api\ApiMessages;

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
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
        
    }

    //==============SALVAR====================================
    public function store(RealStateRequest $request)
    {   
        //variavel recebendo tudo que foi enviado
        $data = $request->all();
        try{
            //salvo o que foi digitado
            $realState = $this->realState->create($data);


            //Aqui eu só to sincronizando os id's
            if(isset($data['categories']) && count($data['categories'])) {
    			$realState->categories()->sync($data['categories']);
            }
            

            //retorno o que foi salvo
        return response()->json([
            'data' => [
                'msg' => 'Imóvel cadastrado com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            //sinão eu retorno essa mensagem de erro
            return response()->json($message->getMessage(), 401);
        }
        
    }

    //======================EDITAR===================================
    public function update($id, RealStateRequest $request)
    {   
        //variavel recebendo tudo que foi enviado
        $data = $request->all();
        try{
            //salva nessa variável o imovel que tenha esse id
            $realState = $this->realState->findOrFail($id);
            //chama o metodo update pra esses novos dados que estão na variável $data
            $realState->update($data);

            if(isset($data['categories']) && count($data['categories'])) {
    			$realState->categories()->sync($data['categories']);
            }
            

            //retorno a mensagem de atualizado e o StatusCode
        return response()->json([
            'data' => [
                'msg' => 'Imóvel atualizado com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            //sinão eu retorno essa mensagem de erro
            return response()->json($message->getMessage(), 401);
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
            $message = new ApiMessages($e->getMessage());
             //sinão, eu retorno essa mensagem de erro com StatusCode
             return response()->json($message->getMessage(), 401);
         }
         
     }

    
}
