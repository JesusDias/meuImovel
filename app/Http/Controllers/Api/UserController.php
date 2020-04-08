<?php

namespace App\Http\Controllers\Api;
use App\Api\ApiMessages;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

//use App\Http\Requests\UserRequest;

use Illuminate\Http\Request;


class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate('10');

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //variavel recebendo tudo que foi enviado
         $data = $request->all();
        //se na requisição não houver campo password ele ja cai no if
        //se houver mas estiver vazio ele tbm cai
         if(!$request->has('password') || !$request->get('password')) {
             $message = new ApiMessages('É necessário informar uma senha para usuário...');
             return response()->json($message->getMessage(), 401);
         }

         //Vamos fazer uma validação nesses dados de perfil que serão salvos
         Validator::make($data, [
		    'phone' => 'required',
	    	'mobile_phone' => 'required'
	    ])->validate();


         try{
             //salvo o que foi digitado
             $data['password'] = bcrypt($data['password']);
             $user = $this->user->create($data);
             

             //salvando os dados de perfil de usuário
             $user->profile()->create(
	    		[
	    			'phone' => $data['phone'],
				    'mobile_phone' => $data['mobile_phone']
			    ]
		    );


             //retorno o que foi salvo
         return response()->json([
             'data' => [
                 'msg' => 'Usuário cadastrado com sucesso!'
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
    public function show($id)
    {
        try{
            //busca o imóvel que tenha esse id
            //esse with eu passo o nome da meu array de informações que estão relacionadas
            $user = $this->user->with('profile')->findOrFail($id);

            //assim eu deserializo pra aparecer na forma de um link
            $user->profile->social_networks = unserialize($user->profile->social_networks);
        
            //retorna esse imovel que tenah o id informado com o statusCode
        return response()->json([
            'data' => $user
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
    public function update(Request $request, $id)
    {
        //variavel recebendo tudo que foi enviado
        $data = $request->all();

        if($request->has('password') && $request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        //e tbm valida esses dados, que são obrigatórios
        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required'
        ])->validate();

        try{
            //me trz o que foi passado nesse array profile
            $profile = $data['profile'];
            //agora serializa e atribui de volta na opção do array social_networks 
            $profile['social_networks'] = serialize($profile['social_networks']);


            //salva nessa variável o imovel que tenha esse id
            $user = $this->user->findOrFail($id);
            //chama o metodo update pra esses novos dados que estão na variável $data
            $user->update($data);

            //chama o relacionamento e atualiza os dados que estão no profile
            $user->profile()->update($profile);

            //retorno a mensagem de atualizado e o StatusCode
        return response()->json([
            'data' => [
                'msg' => 'Usuário atualizado com sucesso!'
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
    public function destroy($id)
    {
        try{
            //busca esse imovel que tenha esse id
            $user = $this->user->findOrFail($id);
            //deleta o imovel que tenha esse id
            $user->delete($id);

            //retorno a mensagemde imovel removido e statusCode 
        return response()->json([
            'data' => [
                'msg' => 'Usuário removido com sucesso!'
            ]
        ], 200);

        } catch (\Exception $e) {
           $message = new ApiMessages($e->getMessage());
            //sinão, eu retorno essa mensagem de erro com StatusCode
            return response()->json($message->getMessage(), 401);
        }
    }
}
