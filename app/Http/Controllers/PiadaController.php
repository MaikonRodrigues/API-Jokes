<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Piada;

class PiadaController extends Controller  
{
    /*
    |--------------------------------------------------------------------------
    | Views Functions
    |--------------------------------------------------------------------------
    */
    
     /*Funcao chama view Login
    Public function viewLogout(){       
        return view('vendor/adminlte/login');
    }

     // Funcao chama view Register
     Public function viewRegister(){       
        return view('vendor/adminlte/register');
    }*/

    Public function viewIndex(){
        $piadas = Piada::all();
        //dd($piadas);
        $array_piadas = array("piadas"=>$piadas);
        return view("home", $array_piadas);
    }

    // Funcao chama view Cria piada
    Public function ViewCreatePiada(){
        if (auth()->check()) {
            return view('create_piada');
        }else {
            return redirect('/login');
        }
       
    }

    // Funcao chama a view Editar piada
    Public function viewEditar($id){
        $piada = Piada::find($id);
        
        return view('editar',$piada);
    }
    
    //  $phone = User::find(1)->phone;

    /*
    |--------------------------------------------------------------------------
    | Crud Functions
    |--------------------------------------------------------------------------
    */

    // Funcao insere nova piada no banco
    public function createPiada(Request $request){
       try{
            $piadas = new Piada;
            $piadas->descricao = $request->descricao_form;            
            $piadas->user_id = auth()->user()->id;
            $piadas->save();
            return redirect('/')->with('success', 'Piada adicionada com sucesso');

       }catch(\Exepction $erro){

        return redirect('/')->with('erro', 'Ocorreu um erro ao adicionar');

       }        
    }

    // Funcao para exclui piada
    Public function delete($id){
        try{
            $piada = Piada::find($id);
            $piada->delete();

            return redirect('/')->with('success', 'Piada excluida com sucesso');
            
        }catch(\Exepction $erro){

            return redirect('/')->with('erro', 'Ocorreu um erro ao deletar');
    
        }    
       
    }

    // Funcao para salvar edicao piada
    Public function update(Request $request){
        try{
            $piadas = Piada::find($request->id_form);

            $piadas->descricao = $request->descricao_form;
            $piadas->save();

            return redirect('/')->with('success', 'Piada editada com sucesso');
            
        }catch(\Exepction $erro){

            return redirect('/')->with('erro', 'Ocorreu um erro ao atualizar');
    
        }    
    }
}
 