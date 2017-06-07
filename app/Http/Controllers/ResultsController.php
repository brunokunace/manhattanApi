<?php

namespace App\Http\Controllers;

use App\RateTypes;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Results;
use App\HistoricalResults;
use Tymon\JWTAuth\Facades\JWTAuth;
use League\Csv\Reader;


class ResultsController extends Controller
{
    public function import(Request $request){
        function moeda($get_valor) {

            $source = array('.', ',');
            $replace = array('', '.');
            $valor = str_replace($source, $replace, $get_valor);
            return $valor;
        }
        if($request->hasFile('importFile')){
            $path = $request->file('importFile')->getRealPath();
            $filename = $request->file('importFile')->getClientOriginalName();
            $csv = Reader::createFromPath($path);
            $csv->setDelimiter(';');
            $data = $csv->setOffset(1)->fetchAll();

            $code = sha1(time());
            $historical = HistoricalResults::create([
                'code' => $code,
                'filename' => $filename
            ]);
            if(!empty($data)){
                foreach ($data as $key => $value) {
                    if(empty($value[3])){
                        $value[3]= 0;
                        $value[4] = "Master";
                    }
                    $prefix = substr($value[6], 0, 3);
                    $rates_types = [
                        'WDO' => 1,
                        'DOL' => 2,
                        'IND' => 3,
                        'WIN' => 4
                        ];
                    $rate_type_id = isset($rates_types[$prefix]) ? $rates_types[$prefix] : 5;
                    $rate_type = RateTypes::find($rate_type_id);

                    $preco_medio = moeda($value[14]);
                    $preco = moeda($value[11]);
                    $total = moeda($value[17]);
                    $total_executado = moeda($value[18]);
                    $quantidade_executada = moeda($value[15]);
                    $quantidade = moeda($value[13]);
                    $value[9] = str_pad($value[9],19,":00");
                    $value[10] = str_pad($value[10],19,":00");

                    $criacao = DateTime::createFromFormat('d/m/Y H:i:s', $value[9]);
                    $criacao->format('Y-m-d H:i:s');
                    $ultima_atualizacao = DateTime::createFromFormat('d/m/Y H:i:s', $value[10]);
                    $ultima_atualizacao->format('Y-m-d H:i:s');
                    Results::create([
                        'corretora' => $value[0],
                        'conta' => $value[1],
                        'titular' => $value[2],
                        'sub_conta' => $value[3],
                        'sub_titular' => $value[4],
                        'clordid' => $value[5],
                        'ativo' => $value[6],
                        'lado' => $value[7],
                        'status' => $value[8],
                        'criacao' => $criacao,
                        'ultima_atualizacao' => $ultima_atualizacao,
                        'preco' => $preco,
                        'preco_stop' => $value[12],
                        'quantidade' => $quantidade,
                        'preco_medio' => $preco_medio,
                        'quantidade_executada' => $quantidade_executada,
                        'quantidade_restante' => $value[16],
                        'total' => $total,
                        'total_executado' => $total_executado,
                        'validade' => $value[19],
                        'data_validade' => $value[20],
                        'estrategia' => $value[21],
                        'mensagem' => $value[22],
                        'emolumento' => $rate_type->emolumento,
                        'corretagem' => $rate_type->corretagem,
                        'ganho' => $rate_type->ganho,
                        'historical_result_id' => $historical->id,
                        'rate_types_id' => $rate_type->id
                    ]);
                }
                return response()->json(['success' => 'Dados Inseridos com sucesso'],200);
            }
        }
        return response()->json(['error' => 'Erro importar o arquivo'],400);
    }
    public function porAtivo(Request $request){
        $dataInicio = $request->dataInicio;
        $dataFim = $request->dataFim;

        $result = [];
        $rates = RateTypes::all()->except(5);
        foreach ($rates as $rate){
            $query = 'sub_titular, 
                sub_conta,
                ContratosUnico(sub_conta,rate_types_id,"c","'.$dataInicio.'","'.$dataFim.'") AS ContratosC,
                ContratosUnico(sub_conta,rate_types_id,"v","'.$dataInicio.'","'.$dataFim.'") AS ContratosV,
                VolumesUnico(sub_conta,rate_types_id,"c","'.$dataInicio.'","'.$dataFim.'") AS VolumesC,
                VolumesUnico(sub_conta,rate_types_id,"v","'.$dataInicio.'","'.$dataFim.'") AS VolumesV';
            if($request->has('sub_conta')){
                $results = DB::table('results')
                    ->select(DB::raw($query))
                    ->where('rate_types_id',$rate->id)
                    ->where('sub_conta',$request->sub_conta)
                    ->groupBy('sub_conta')
                    ->get();
            }else{
                $results = DB::table('results')
                    ->select(DB::raw($query))
                    ->where('rate_types_id',$rate->id)
                    ->groupBy('sub_conta')
                    ->get();
            }

            $result[$rate->name] = $results;
            foreach ($result[$rate->name] as $k => $resultInitial){
                if(!$resultInitial->VolumesC > 0){
                    unset($result[$rate->name][$k]);
                    continue;
                }
                $resultInitial->VolumesC = round($resultInitial->VolumesC / $resultInitial->ContratosC, 5, PHP_ROUND_HALF_UP);
                $resultInitial->VolumesV = round($resultInitial->VolumesV / $resultInitial->ContratosV, 5, PHP_ROUND_HALF_UP);
                $resultInitial->bruto = round(($resultInitial->VolumesV - $resultInitial->VolumesC) * $resultInitial->ContratosC * $rate->ganho, 2, PHP_ROUND_HALF_UP);
                $resultInitial->custo = round(($resultInitial->ContratosC + $resultInitial->ContratosV) / 2 * (($rate->emolumento + $rate->corretagem) * -2), 2, PHP_ROUND_HALF_UP);
                $resultInitial->net = round($resultInitial->bruto + $resultInitial->custo, 2, PHP_ROUND_HALF_UP);
            }

        }
        return response()->json($result,200);

    }
    public function porAcao(Request $request){
        $dataInicio = $request->dataInicio;
        $dataFim = $request->dataFim;

        $result = [];
        $rates = RateTypes::find(5);
            $query = 'sub_titular, 
                sub_conta,
                ContratosUnico(sub_conta,rate_types_id,"c","'.$dataInicio.'","'.$dataFim.'") AS ContratosC,
                ContratosUnico(sub_conta,rate_types_id,"v","'.$dataInicio.'","'.$dataFim.'") AS ContratosV,
                AcaoVolumesUnico(sub_conta,rate_types_id,"c","'.$dataInicio.'","'.$dataFim.'") AS VolumesC,
                AcaoVolumesUnico(sub_conta,rate_types_id,"v","'.$dataInicio.'","'.$dataFim.'") AS VolumesV';
            if($request->has('sub_conta')){
                $results = DB::table('results')
                    ->select(DB::raw($query))
                    ->where('rate_types_id',$rates->id)
                    ->where('sub_conta',$request->sub_conta)
                    ->groupBy('sub_conta')
                    ->get();
            }else{
                $results = DB::table('results')
                    ->select(DB::raw($query))
                    ->where('rate_types_id',$rates->id)
                    ->groupBy('sub_conta')
                    ->get();
            }

            $result[$rates->initial] = $results;
            foreach ($result[$rates->initial] as $k => $resultInitial){
                if(!$resultInitial->VolumesC > 0){
                    unset($result[$rates->initial][$k]);
                    continue;
                }
                $resultInitial->VolumesC = round($resultInitial->VolumesC, 2, PHP_ROUND_HALF_UP);
                $resultInitial->VolumesV = round($resultInitial->VolumesV, 2, PHP_ROUND_HALF_UP);
                $resultInitial->bruto = round(($resultInitial->VolumesV - $resultInitial->VolumesC), 2, PHP_ROUND_HALF_UP);
                $resultInitial->custo = round(($resultInitial->VolumesC + $resultInitial->VolumesV) * (($rates->emolumento + $rates->corretagem) * -1), 2, PHP_ROUND_HALF_UP);
                $resultInitial->net = round($resultInitial->bruto + $resultInitial->custo, 2, PHP_ROUND_HALF_UP);
            }


        return response()->json($result,200);

    }
    public function subtitular( ){

        $results = Results::select('sub_conta', 'sub_titular')
            ->groupBy('sub_conta')
            ->get();
        return response()->json($results,200);
    }
}
