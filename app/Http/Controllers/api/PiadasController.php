<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Piada;

class PiadasController extends Controller
{
    //Busca todas as piadas
    public function piadas(){
        return Piada::all();
    }
    // Busca piada pelo id
    public function getPiadas($id){
        $piada = Piada::find($id);
        return $piada;
    }
    //Adiciona piada
    public function addPiada(Request $req){
        try{
            $piadas = new Piada;
            $piadas->descricao = $req->descricao_app;
            $piadas->save();

            return['insert', 'ok'];

        }catch(\Exception $erro){

            return['insert', 'erro'];
        }
    }
    //Atualizar piada
    public function atualizarPiada(Request $request, $id){
        try{
            $piadas = Piada::find($id);
            $piadas->descricao = $req->descricao_app;
            $piadas->save();

            return['update', 'ok'];

        }catch(\Exception $erro){

            return['update', 'erro'];
        }
    }
    public function deletarPiada($id){
        try{
            $piada = Piada::find($id);
            $piada->delete();
            return['delete', 'ok'];

        }catch(\Exception $erro){

            return['delete', 'erro'];
        }
    }

    
} 
