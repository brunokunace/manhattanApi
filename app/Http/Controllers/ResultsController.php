<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Results;
use App\HistoricalResults;
use Tymon\JWTAuth\Facades\JWTAuth;


class ResultsController extends Controller
{
    public function import(Request $request){
        if($request->hasFile('import_file')){
            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, null, 'ISO-8859-1')->get();

            $code = sha1(time());
            $historical = HistoricalResults::create([
                'code' => $code
            ]);
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    Results::create([
                        'corretora' => $value->corretora,
                        'conta' => $value->conta,
                        'titular' => $value->titular,
                        'sub_conta' => $value->subconta,
                        'sub_titular' => $value->subtitular,
                        'clordid' => $value->clordid,
                        'ativo' => $value->ativo,
                        'lado' => $value->lado,
                        'status' => $value->status,
                        'criacao' => $value->criacao,
                        'ultima_atualizacao' => $value->ultima_atualizacao,
                        'preco' => $value->preco,
                        'preco_stop' => $value->preco_stop,
                        'quantidade' => $value->qtd,
                        'preco_medio' => $value->preco_medio,
                        'quantidade_executada' => $value->qtd_executada,
                        'quantidade_restante' => $value->qtd_restante,
                        'total' => $value->total,
                        'total_executado' => $value->total_executado,
                        'validade' => $value->validade,
                        'data_validade' => $value->data_validade,
                        'estrategia' => $value->estrategia,
                        'mensagem' => $value->mensagem,
                        'historical_result_id' => $historical->id
                    ]);
                }
                return response()->json(['status' => 'Dados Inseridos com sucesso'],200);
            }
        }
        return response()->json(['error' => 'Erro importar o arquivo'],400);
    }
    public function all(){
        if($results = Results::orderByDesc('criacao')->get()){
            return response()->json($results,200);
        }
        return response()->json(['error' => 'Não foi possível completar a operação'],400);
    }
    public function me(){

        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);

        if($results = Results::where('sub_conta',$user->cod_subconta)->orderByDesc('criacao')->get()){
            return response()->json($results,200);
        }
        return response()->json(['error' => 'Não foi possível completar a operação'],400);
    }
}
