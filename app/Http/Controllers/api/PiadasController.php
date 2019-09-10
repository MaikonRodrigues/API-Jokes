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
    public function getLink(){
        return 'https://pt.stackoverflow.com/questions/164911/bot%C3%A3o-compartilhar';
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

    public function postLikePiada(Request $request)
    {
        $temLike = 0;
        $piada = Piada::find($request->piada_id);
        
        $user = User::find($request->user_id);

        $likes = DB::table('likes')->get();

        foreach ($likes as $like) { 
           // dd($like->user_id ."==". $user->id .",". $like->piada_id ."==". $piada->id);           
           if($like->user_id == $user->id){
               if ($like->piada_id == $request->piada_id) {
                   $temLike = 1;
                    if ($like->like == '0') {
                        $piada->curtidas+=1;
                        $piada->save();    
                        DB::table('likes')->where('id', $like->id)->update(['like' => 1]);
                        
                        return [$piada];
                    
                    }else{
                        
                        $piada->curtidas-=1;
                        $piada->save();
                        DB::table('likes')->where('id', $like->id)->update(['like' => 0]);
                        
                        return [$piada]; 
                                        
                    }               
               }               
            }
             
        }
        if ($temLike == 0) {                                      
            $newLike = Like::create([
                'user_id' =>  $user->id,
                'piada_id' => $piada->id,
                'like' => 1 //set true
            ]);            

            $piada->curtidas+=1;
            $piada->save();
            return [$piada];          
        }
    }

    public function postDsLikePiada(Request $request)
    {
        $temdesLike = 0;
        $piada = Piada::find($request->piada_id);
        
        $user = User::find($request->user_id);

        $deslikes = DB::table('des_likes')->get();

        foreach ($deslikes as $deslike) { 
                  
           if($deslike->user_id == $user->id){
               if ($deslike->piada_id == $request->piada_id) {
                   $temdesLike = 1;
                    if ($deslike->deslike == '0') {
                        $piada->deslikes+=1;
                        $piada->save();    
                        DB::table('des_likes')->where('id', $deslike->id)->update(['deslike' => 1]);
                        
                        return [$piada];
                    
                    }else{
                        
                        $piada->deslikes-=1;
                        $piada->save();
                        DB::table('des_likes')->where('id', $deslike->id)->update(['deslike' => 0]);
                        
                        return [$piada]; 
                                        
                    }               
               }               
            }
             
        }
        if ($temdesLike == 0) {                                      
            $newDesLike = DesLike::create([
                'user_id' =>  $user->id,
                'piada_id' => $piada->id,
                'deslike' => 1 //set true
            ]);            

            $piada->deslikes+=1;
            $piada->save();
            return [$piada];          
        }
    }



} 
