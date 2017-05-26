<?php

namespace App\Http\Controllers;

use App\RateTypes;
use Illuminate\Http\Request;

class RateTypesController extends Controller
{
    public function create(Request $request){
        $rate_types = RateTypes::create([
            'name' => $request->name,
            'initial' => $request->initial,
            'emolumento' => $request->emolumento,
            'corretagem' => $request->corretagem,
            'ganho' => $request->ganho
        ]);
        if(!$rate_types){
            return response()->json(['error' => 'Não foi possível criar o recurso'],400);
        }
        return response()->json(['success' => 'Recurso criado com sucesso.'],200);
    }
    public function update(Request $request){
        $rate_types = $request->data;
        var_dump($rate_types);
        foreach ($rate_types as $rate_type){

            $rate = RateTypes::find($rate_type['id']);
            $rate->name = $rate_type['name'];
            $rate->initial = $rate_type['initial'];
            $rate->emolumento = $rate_type['emolumento'];
            $rate->corretagem = $rate_type['corretagem'];
            $rate->ganho = $rate_type['ganho'];

            $rate->save();
            if(!$rate){
                return response()->json(['error' => 'Não foi possível atualizar o recurso'],400);
            }
        }
        return response()->json(['success' => 'Recurso criado com sucesso.'],200);
    }
    public function all (){
        $rate_types = RateTypes::all();
        if(!$rate_types){
            return response()->json(['error' => 'Não foi possível encontrar o recurso'],400);
        }
        $data['data'] = $rate_types;
        return response()->json($data,200);
    }
}
