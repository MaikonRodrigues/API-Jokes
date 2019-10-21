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
                        $piada->curtidas-=1;
                        $piada->deslikes+=1;
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

    // Funcao que busca todas as reacoes 
    public function getReacoes(){
        return Reacao::all();
    }

    public function post_LikePiada(Request $request)
    {
        $temLike = 0;
        $temDesLike = 0;    $deslikId = 0;
        
        $piada = Piada::find($request->piada_id);
        
        $user = User::find($request->user_id);

        $likes = DB::table('likes')->get();
        $dsLikes = DB::table('des_likes')->get();

        foreach ($likes as $like) { 
           // verificando like     
           if($like->user_id == $user->id && $like->piada_id == $request->piada_id){               
                   $temLike = 1;
                    // verificando dslike
                    foreach ($dsLikes as $dlike) { 
                        if($dlike->user_id == $user->id && $dlike->piada_id == $request->piada_id){
                            
                                $temDesLike = 1;    $deslikId = $dlike->id;
                                if ($dlike->deslike == '0') {

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

                                }else{

                                    DB::table('des_likes')->where('id', $dlike->id)->update(['deslike' => 0]);
                                    $piada->deslikes-=1;
                                    $piada->save();

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
                             
            }else {
                foreach ($dsLikes as $dlike) { 
                    if($dlike->user_id == $user->id && $dlike->piada_id == $request->piada_id){                        
                        $temDesLike = 1;    $deslikId = $dlike->id;
                    }
                }
            }
             
        }
        if ($temLike == 0) { 
            if($temDesLike == 0){
                $newLike = Like::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'like' => 1 
                ]);            
    
                $piada->curtidas+=1;
                $piada->save();
                return [$piada];    
            }else{
                DB::table('des_likes')->where('id', $deslikId)->update(['deslike' => 0]);
                $piada->deslikes-=1;
                $newLike = Like::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'like' => 1 
                ]);            
    
                $piada->curtidas+=1;
                $piada->save();
                return [$piada];    
            }                                     
                 
        }else{
            if($temDesLike == 0){
                $newLike = Like::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'like' => 1 
                ]);            
    
                $piada->curtidas+=1;
                $piada->save();
                return [$piada];    
            }else{
                DB::table('des_likes')->where('id', $deslikId)->update(['deslike' => 0]);
                $piada->deslikes-=1;
                $newLike = Like::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'like' => 1 
                ]);            
    
                $piada->curtidas+=1;
                $piada->save();
                return [$piada];    
            }                    
        }
    }

    public function postLikePiada(Request $request){

        //Pegando a piada e o usuario atravez das informações request
        $piada = Piada::find($request->piada_id);    $user = User::find($request->user_id);
        //Pegando o usuario via id da request
        $user = User::find($request->user_id);
        // Buscando todos os likes e dslikes
        $deslikes = DB::table('des_likes')->get();
        $likes = DB::table('likes')->get();
        //  Inicialisando variaveis
        $temdesLike = 0; $temLike = 0;
        // verificando nas tabelas likes e  dslikes a existencia de likes e dslikes com esses ids
        foreach ($deslikes as $deslike) { 
            if($deslike->user_id == $user->id && $deslike->piada_id == $request->piada_id){  // Se passar tem deslike
                $temdesLike = 1;
                $id_do_dslike = $deslike->id;   // se tiver dslike pego o id dele
            }else{
                $temdesLike = 0;
            }
        }
        foreach ($likes as $like) { 
            if($like->user_id == $user->id && $like->piada_id == $request->piada_id){  // Se passar tem deslike
                $temLike = 1;
                $id_do_like = $like->id;    // se tiver like pego o id dele
            }else{
                $temLike = 0;
            }
        }
        // ifs de acordo com o resultado da consulta acima
        if($temLike == 0 && $temdesLike == 0){  //Piada nao tem nenhuma curtina e nem um dslike
            $newLike = Like::create([       //  Crio new like 
                'user_id' =>  $user->id,    // com id do usuario e da piada
                'piada_id' => $piada->id,
                'like' => 1                 //  set como 1
            ]);            

            $piada->curtidas+=1;        // incremento a curtida da piada
            $piada->save();
            return [$piada];            
        }else if($temLike == 0 && $temdesLike == 1){
            $newLike = Like::create([       //  Crio new like 
                'user_id' =>  $user->id,    // com id do usuario e da piada
                'piada_id' => $piada->id,
                'like' => 1                 //  set como 1
            ]);            

            $piada->curtidas+=1;        // incremento a curtida da piada
            DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 0]);   // set dslike como 0 com base no id que foi pego
            $piada->deslikes-=1;        // decremento o dslike
            $piada->save();
            return [$piada];  
        }else if($temLike == 1 && $temdesLike == 0){
            $like_exist = Like::find($id_do_like); 
            if($like_exist->like == 1){
                DB::table('likes')->where('id', $id_do_like)->update(['like' => 0]);
                $piada->curtidas-=1;    //decremento a curtida 
                $piada->save();
                return [$piada];    
            }else{
                DB::table('likes')->where('id', $id_do_like)->update(['like' => 1]);
                $piada->curtidas+=1;    //decremento a curtida 
                $piada->save();
                return [$piada];    
            }
            /*
            DB::table('likes')->where('id', $id_do_like)->update(['like' => 1]);    //set o like para 1
            $piada->curtidas+=1;    //incremento a curtida 
            $piada->save();
            return [$piada];
            */
        }else if($temLike == 1 && $temdesLike == 1){
            // Pego o like e o dslike existente
            $like_exist = Like::find($id_do_like);  $dslike_exist = DesLike::find($id_do_dslike);

            //verificando se ja tem um like ou dslike
            if($like_exist->like == 0 && $dslike_exist->deslike == 0){

                DB::table('likes')->where('id', $id_do_like)->update(['like' => 1]);
                $piada->curtidas+=1;    //incremento a curtida 
                $piada->save();
                return [$piada];

            }else if($like_exist->like == 1 && $dslike_exist->deslike == 0){
                
                DB::table('likes')->where('id', $id_do_like)->update(['like' => 0]);
                $piada->curtidas-=1;    //decremento a curtida 
                $piada->save();
                return [$piada];

            }else if($like_exist->like == 0 && $dslike_exist->deslike == 1){

                DB::table('likes')->where('id', $id_do_like)->update(['like' => 1]);
                $piada->curtidas+=1;    //incremento a curtida 
                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 0]);   // set dslike como 0 com base no id que foi pego
                $piada->deslikes-=1;        // decremento o dslike
                $piada->save();
                return [$piada];  
            }
            
        }
    }

    public function  postDsLikePiada(Request $request){
        //Pegando a piada e o usuario atravez das informações request
        $piada = Piada::find($request->piada_id);    $user = User::find($request->user_id);
        //Pegando o usuario via id da request
        $user = User::find($request->user_id);
        // Buscando todos os likes e dslikes
        $deslikes = DB::table('des_likes')->get();
        $likes = DB::table('likes')->get();
        //  Inicialisando variaveis
        $temdesLike = 0; $temLike = 0;
        // verificando nas tabelas likes e  dslikes a existencia de likes e dslikes com esses ids
        foreach ($deslikes as $deslike) { 
            if($deslike->user_id == $user->id && $deslike->piada_id == $piada->id){  // Se passar tem deslike
                $temdesLike = 1;
                $id_do_dslike = $deslike->id;   // se tiver dslike pego o id dele
            }else{
                $temdesLike = 0;
            }
        }
        foreach ($likes as $like) { 
            if($like->user_id == $user->id && $like->piada_id == $request->piada_id){  // Se passar tem deslike
                $temLike = 1;
                $id_do_like = $like->id;    // se tiver like pego o id dele
            }else{
                $temLike = 0;
            }
        }
        // ifs de acordo com o resultado da consulta acima
        if($temLike == 0 && $temdesLike == 0){  //Piada nao tem nenhuma curtina e nem um dslike
            $newDesLike = DesLike::create([
                'user_id' =>  $user->id,        //  Crio new dslike 
                'piada_id' => $piada->id,       // com id do usuario e da piada
                'deslike' => 1                  //  set como 1
            ]);     

            $piada->deslikes+=1;        // incremento a curtida da piada
            $piada->save();
            return [$piada];            
        }else if($temdesLike == 1 && $temLike == 0 ){
            $dslike_exist = DesLike::find($id_do_dslike);
            if($dslike_exist->deslike == 0){
                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 1]);    //set o like para 1
                $piada->deslikes+=1;    //incremento a curtida 
                $piada->save();
                return [$piada];
            }else if($dslike_exist->deslike == 1){
                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 0]);    //set o like para 1
                $piada->deslikes-=1;    //incremento a curtida 
                $piada->save();
                return [$piada];
            }        
        }else if($temdesLike == 0 && $temLike == 1 ){
            $newDesLike = DesLike::create([
                'user_id' =>  $user->id,        //  Crio new dslike 
                'piada_id' => $piada->id,       // com id do usuario e da piada
                'deslike' => 1                  //  set como 1
            ]);        

            DB::table('likes')->where('id', $id_do_like)->update(['deslike' => 0]);   // set dslike como 0 com base no id que foi pego
            $piada->curtidas-=1;        // incremento a curtida da piada
            $piada->deslikes+=1;        // decremento o dslike
            $piada->save();
            return [$piada];  
        }else if($temLike == 1 && $temdesLike == 1){
            // Pego o like e o dslike existente
            $like_exist = Like::find($id_do_like);  $dslike_exist = DesLike::find($id_do_dslike);

            //verificando se ja tem um like ou dslike
            if($like_exist->like == 0 && $dslike_exist->deslike == 0){

                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 1]);
                $piada->deslikes+=1;    //incremento a curtida 
                $piada->save();
                return [$piada];

            }else if($like_exist->like == 1 && $dslike_exist->deslike == 0){

                DB::table('likes')->where('id', $id_do_like)->update(['like' => 0]);
                $piada->curtidas-=1;    //incremento a curtida 
                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 1]);   // set dslike como 0 com base no id que foi pego
                $piada->deslikes+=1;        // decremento o dslike
                $piada->save();
                return [$piada];                

            }else if($like_exist->like == 0 && $dslike_exist->deslike == 1){

                DB::table('des_likes')->where('id', $id_do_dslike)->update(['deslike' => 0]);
                $piada->deslikes-=1;    //decremento a curtida 
                $piada->save();
                return [$piada];
                 
            }
            
        }
    }

    public function post_DsLikePiada(Request $request)
    {
        $temdesLike = 0;   $temLike = 0;   // $likId = 0;

        $piada = Piada::find($request->piada_id);
        
        $user = User::find($request->user_id);

        $deslikes = DB::table('des_likes')->get();
        $likes = DB::table('likes')->get();

        foreach ($deslikes as $deslike) { 
            // verificando deslike       
           if($deslike->user_id == $user->id && $deslike->piada_id == $request->piada_id){  // Se passar tem deslike
                    $temdesLike = 1;
                    // verificando like 
                    foreach ($likes as $like) { 

                        if($like->user_id == $user->id && $like->piada_id == $request->piada_id){   // Se passar tem like                                                    
                            $temLike = 1;    $likId = $like->id;

                            if ($like->like == '0') {

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

                            }else{
                               
                                DB::table('likes')->where('id', $like->id)->update(['like' => 0]);
                                $piada->curtidas-=1;
                                $piada->save();

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
                            
            }  else {   // Não tem deslike
                foreach ($likes as $like) { 
                    if($like->user_id == $user->id && $like->piada_id == $request->piada_id){   // Se passar tem like                                                    
                        $temLike = 1;    $likId = $like->id;
                    }
                }
            }           
        }
        if ($temdesLike == 0) {
            if ($temLike == 0) { 
                $newDesLike = DesLike::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'deslike' => 1 //set true
                ]);     
                $piada->deslikes+=1;
                $piada->save();
                return [$piada];    
            }else{
                DB::table('likes')->where('id', $likId)->update(['like' => 0]);
                
                $newDesLike = DesLike::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'deslike' => 1 //set true
                ]); 
                $piada->curtidas-=1;     
                $piada->deslikes+=1;
                $piada->save();
                return [$piada];   
            }                                     
                   
        }else{
            if ($temLike == 0) { 
                $newDesLike = DesLike::create([
                    'user_id' =>  $user->id,
                    'piada_id' => $piada->id,
                    'deslike' => 1 //set true
                ]);     
                $piada->deslikes+=1;
                $piada->save();
                return [$piada];    
            }else{
                DB::table('likes')->where('id', $likId)->update(['like' => 0]);
                $piada->curtidas-=1;
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



} 
