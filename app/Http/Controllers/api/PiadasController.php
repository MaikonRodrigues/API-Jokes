<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Piada;
use App\User; 

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
    // Busca de usuario pelo id
    public function getUser($id){
        $user = User::find($id);        
        return $user;
    }
    //Adiciona piada
    public function addPiada(Request $request){
        try{
            $piadas = new Piada;
            $piadas->descricao = $request->descricao_app;
            $piadas->save();

            return 'ok';

        }catch(\Exception $erro){

            return 'erro';
        }
    }
    //Atualizar piada
    public function atualizarPiada(Request $request, $id){
        try{
            $piadas = Piada::find($id);
            $piadas->descricao = $request->descricao_app;
            $piadas->save();

            return 'ok';

        }catch(\Exception $erro){

            return 'erro';
        }
    }
    public function deletarPiada($id){
        try{
            $piada = Piada::find($id);
            $piada->delete();
            return 'ok';

        }catch(\Exception $erro){

            return 'erro';
        }
    }

    
} 
