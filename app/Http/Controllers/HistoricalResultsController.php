<?php

namespace App\Http\Controllers;

use App\HistoricalResults;

class HistoricalResultsController extends Controller
{
    public function all(){
        $historicals = HistoricalResults::orderByDesc('created_at')->get();

        return response()->json($historicals,200);
    }
    public function delete($id){

        if(HistoricalResults::find($id)->delete()){
            return response()->json(['success' => 'Dados excluídos com sucesso!'],200);
        }
        return response()->json(['error' => 'Não foi possível completar a operação'],400);
    }
}
