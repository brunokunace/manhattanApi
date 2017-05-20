<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use z1haze\Acl\Models\Level;

class ApiAuthController extends Controller
{
    public function authenticate()
    {
        $credentials = request()->only('cpf', 'password');
        try
        {
            $token = JWTAuth::attempt($credentials);
            if(!$token){

                $credentials = request()->only('email', 'password');
                try
                {
                    $token = JWTAuth::attempt($credentials);
                    if(!$token){

                        return response()->json(['error' => 'Dados Incorretos'], 401);
                    }
                }catch (JWTException $e)
                {
                    return response()->json(['error' => 'something_wrong'],500);
                }
            }
        }catch (JWTException $e)
        {
            return response()->json(['error' => 'something_wrong'],500);
        }

        return response()->json(['token' => $token], 200);

    }
    public function register()
    {
        $email = request()->email;
        $name = request()->name;
        $password = request()->password;
        $cpf = request()->cpf;
        $rg = request()->rg;
        $rg_emissor = request()->rg_emissor;
        $rg_uf = request()->rg_uf;
        $data_nascimento = request()->data_nascimento;
        $sexo = request()->sexo;
        $naturalidade = request()->naturalidade;
        $estado_civil = request()->estado_civil;
        $cep = request()->cep;
        $logradouro = request()->logradouro;
        $numero = request()->numero;
        $bairro = request()->bairro;
        $localidade = request()->localidade;
        $uf = request()->uf;
        $skype = request()->skype;
        $telefone_fixo = request()->telefone_fixo;
        $telefone_celular = request()->telefone_celular;
        $cod_subconta = request()->cod_subconta;
        $level = 3;
        if(request()->level){
            $level = request()->level;
        }
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'cpf' => $cpf,
            'rg' => $rg,
            'rg_emissor' => $rg_emissor,
            'rg_uf' => $rg_uf,
            'data_nascimento' => $data_nascimento,
            'sexo' => $sexo,
            'naturalidade' => $naturalidade,
            'estado_civil' => $estado_civil,
            'cep' => $cep,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'bairro' => $bairro,
            'localidade' => $localidade,
            'uf' => $uf,
            'skype' => $skype,
            'telefone_fixo'=> $telefone_fixo,
            'telefone_celular'=> $telefone_celular,
            'cod_subconta' => $cod_subconta,
            'level_id' => $level
        ]);
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token],200);
    }
    public function me()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $level = Level::find($user->level_id);
        $user->setAttribute('role', $level->name);

        return response()->json(['data' => $user],200);
    }
    public function users()
    {
        $users = User::all();
        foreach ($users as $user){
            $level = Level::find($user->level_id);
            $user->setAttribute('role', $level->name);
            $_users[] = $user;
        }
        return response()->json($_users,200);
    }
    public function delete($id){

        if(User::find($id)->delete()){
            return response()
                ->json(['success' => 'Usuario excluido com sucesso!'], 200);
        }
        return response()->json(['error' => 'Não foi possível completar a operação'],400);
    }
}