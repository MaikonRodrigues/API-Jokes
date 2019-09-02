<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Piada;
use App\Categoria;
use Response;
use App\User; 
use App\Like;
use Auth;


class PiadasController extends Controller
{
    /**
     *  if ($user->avatar == "default.png") {
     *       // Salvando nova imagem
     *       $path = public_path().'/uploads/avatars/'.$fileName;
     *       return Response::download($path); 
     *   }else{
    *        //  Deletar foto antiga
     *       File::delete('/uploads/avatars/'.$user->avatar);
    *        // Salvando nova imagem
     *       $path = public_path().'/uploads/avatars/'.$fileName;
     *       return Response::download($path); 
     *   }            
     */
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
    public function postLikePiada(Request $piadaId)
    {
        $piada_id = $piadaId;
        $is_like = true;
        $update = false;
        $piada = Piada::find($post_id);
        if (!$piada) { 
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('piada_id', $piada_id)->first();
        if ($like) {
            $already_like = $like->like;
            $update = true;
            if ($already_like == $is_like) {
                $like->delete();
                return 'deletado';
            }
        } else {
            $like = new Like();
        }
        $like->like = $is_like;
        $like->user_id = $user->id;
        $like->piada_id = $piada->id;
        if ($update) {
            $like->update();
        } else {
            $like->save();
        }
        return 'ok';
    }

    
} 
