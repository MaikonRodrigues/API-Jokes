<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Piada;
use App\Categoria;
use Response;
use App\User; 
use App\Like;
use App\DesLike;
use App\react;
use Auth;


class PiadasController extends Controller
{
   
    public function getImage($fileName){       
               
        $path = public_path().'/uploads/avatars/'.$fileName;
        return Response::download($path); 
       
    }
    //Busca todas as piadas
    public function piadas(){
        return Piada::all();
    }
    // Busca todas as categorias 
    public function getCategorias(){               
        return Categoria::all();
    }
    // Busca piada pelo id
    public function getPiadas($id){
        $piada = Piada::find($id);
        return $piada;
    }
    // Busca de usuario pelo id
    public function getUser($id){
        $user = User::find($id);        
        return User::all();
    }
   
    //Adiciona piada
    public function addPiada(Request $request){
        try{
            $piadas = new Piada;
            $piadas->descricao = $request->descricao_app;            
            $piadas->user_id = $request->user_id_app;
            $piadas->categoria_id = $request->categoria_app;
            $piadas->save();

            return 'ok';

        }catch(\Exception $erro){

            return $erro;
        }
    }
    public function newReact(Request $request){
        $reacoes = react::all();   $temReacao = 0; $piada = Piada::find($request->piada_id);
        /*
        *    Se a reacao for = 0 retorno a piada. quando passo 0 preciso somente das informacoes de reacoes
        *    Se a reacao for = 1 entao modifico a reacao para like e incremento a curtida
        *    Se a reacao for = 2 entao modifico a reacao para deslike e incremento dslike
        */
        if($request->reacao == 0){
            return [$piada];
        }else{
            foreach($reacoes as $reac){
                if($reac->piada_id == $request->piada_id && $reac->user_id == $request->user_id){
                    if($reac->reacao == 1 && $request->reacao == 1){    // User enviou um like onde ja tinha um like
                        $reac->reacao = 0;  $reac->save();  // set a reacao como 0
                        $piada->curtidas-=1;    // subtrai uma curtida
                        $piada->save();
                        return [$piada];
                    }else if($reac->reacao == 1  && $request->reacao == 2){ // User enviou um dislike onde ja tinha um like
                        $reac->reacao = 2;  $reac->save();                        
                        $piada->deslikes+=1;
                        $piada->curtidas-=1;
                        $piada->save();
                        return [$piada];
                    }else if($reac->reacao == 0  && $request->reacao == 2){ // User enviou um dislike onde nao havia nenhuma reação
                        $reac->reacao = 2;  $reac->save();
                        $piada->deslikes+=1;
                        $piada->save();
                        return [$piada];
                    }else if($reac->reacao == 0  && $request->reacao == 1){ // User enviou um like onde ja tinha um like
                        $reac->reacao = 1;  $reac->save();
                        $piada->curtidas+=1;
                        $piada->save();
                        return [$piada];
                    }else if($reac->reacao == 2  && $request->reacao == 1){ // User enviou um like onde ja tinha um dislike
                        $reac->reacao = 1;  $reac->save();
                        $piada->curtidas+=1;
                        $piada->deslikes-=1;
                        $piada->save();
                        return [$piada];
                    }
                    else if($reac->reacao == 2  && $request->reacao == 2){  // User enviou um dislike onde ja tinha um dislike
                        $reac->reacao = 0;  $reac->save();
                        $piada->deslikes-=1;
                        $piada->save();
                        return [$piada];
                    } 
                }
            }
            //  Se nao entrar no laço inicial entao nao tem registro de reação na tabela
            if($temReacao == 0){    
               
                $reacao = new react();  // entao e criado uma reacao com o id da piada e de quem curtiu
                $reacao->reacao = $request->reacao;
                $reacao->piada_id = $request->piada_id;
                $reacao->user_id = $request->user_id;
                $reacao->save();
    
                if($request->reacao == 1){  // 1- curtida
                    $piada->curtidas+=1;
                    $piada->save();
                    return [$piada];
                }else if($request->reacao == 2){    // 2- dislike
                    $piada->deslikes+=1;
                    $piada->save();
                    return [$piada];
                } 
               
            }
        }
    }
    public function newCat(Request $request){
        try{
            $categoria = new Categoria();
            $categoria->nome = $request->nomeCat;
            $categoria->estado = 0;
            $categoria->save();

            return 'ok';

        }catch(\Exception $erro){

            return $erro;
        }
    }
    //Atualizar user
    public function atualizarUser(Request $request){
        try{
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time().'.'.$avatar->getClientOriginalExtension();
                Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/'.$filename ));
                $user = Auth::user();
                $user->avatar = $filename;
                $user->save();
                return 'ok';
            }         
            
        }catch(\Exception $erro){

            return 'erro';
        }
    }
    public function getLink(){
        return 'https://link do app aqui';
    }
    //Atualizar piada
    public function atualizarPiada(Request $request, $id){
        try{
            $piadas = Piada::find($id);
            $piadas->descricao = $request->descricao_app;
            $piadas->categoria_id = $request->categoria_app;
            $piadas->save();

            return 'ok';

        }catch(\Exception $erro){

            return 'erro';
        }
    }
    //  Deletar piada
    public function deletarPiada($id){
        try{
            $piada = Piada::find($id);
            $piada->delete();
            return 'ok';
 
        }catch(\Exception $erro){

            return 'erro';
        }
    }
    public function getLike(Request $request){
        $piada = Piada::find($request->piada_id);
        return [$piada];
    }

    // Funcao que busca todas as reacoes 
    public function getReacoes(){
        return Reacao::all();
    }

   


} 
