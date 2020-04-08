<?php

namespace App\Http\Controllers\Api;
use App\Api\ApiMessages;
use App\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
//pode retirar esse request que ja veio automático
//use Illuminate\Http\Request;

class CategoryController extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //==========================LISTAR TODOS=============================================
    public function index()
    {
        $categories = $this->category->paginate('10');

        return response()->json($categories, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //==========================SALVAR======================================================
    public function store(CategoryRequest $request)
    {
          //variavel recebendo tudo que foi enviado
          $data = $request->all();
          //se na requisição não houver campo password ele ja cai no if
          //se houver mas estiver vazio ele tbm cai
           
           try{
               //salvo o que foi digitado
               $category = $this->category->create($data);
               //retorno o que foi salvo
           return response()->json([
               'data' => [
                   'msg' => 'Categoria cadastrada com sucesso!'
               ]
           ], 200);
   
           } catch (\Exception $e) {
               $message = new ApiMessages($e->getMessage());
               //sinão eu retorno essa mensagem de erro
               return response()->json($message->getMessage(), 401);
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //==========================LISTAGEM FILTRANDO ID====================================================
    public function show($id)
    {
        
        try{
            //busca o imóvel que tenha esse id
            $category = $this->category->findOrFail($id);
        
            //retorna esse imovel que tenah o id informado com o statusCode
        return response()->json([
            'data' => $category
        ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            //sinão eu retorno essa mensagem de erro
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //==========================EDITAR====================================================
    public function update(CategoryRequest $request, $id)
    {
        //variavel recebendo tudo que foi enviado
        $data = $request->all();

        try{
            //salva nessa variável o imovel que tenha esse id
            $category = $this->category->findOrFail($id);
            //chama o metodo update pra esses novos dados que estão na variável $data
            $category->update($data);
            //retorno a mensagem de atualizado e o StatusCode
        return response()->json([
            'data' => [
                'msg' => 'Categoria atualizada com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            //sinão eu retorno essa mensagem de erro
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //==========================DELETAR====================================================
    public function destroy($id)
    {
        try{
            //busca esse imovel que tenha esse id
            $category = $this->category->findOrFail($id);
            //deleta o imovel que tenha esse id
            $category->delete($id);

            //retorno a mensagemde imovel removido e statusCode 
        return response()->json([
            'data' => [
                'msg' => 'Categoria removida com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
           $message = new ApiMessages($e->getMessage());
            //sinão, eu retorno essa mensagem de erro com StatusCode
            return response()->json($message->getMessage(), 401);
        }
    }

     //===============LISTAR CATEGORIAS COM IMOVEIS=============================================================
     //esse método está sendo chamado em uma rota específica para chamar os imoveis de uma determinada categoria
     
     public function realState($id)
	{
		try {
			$category = $this->category->findOrFail($id);

			return response()->json([
				'data' => $category->realStates
			], 200);

		} catch (\Exception $e) {
			$message = new ApiMessages($e->getMessage());
			return response()->json($message->getMessage(), 401);
		}
	}
}
