<?php

namespace App\Http\Controllers\api;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Piada;
use Response;
use App\User; 
use Auth;
use Illuminate\Http\Request;
use Image;


class UsersController extends Controller
{
    protected function addUser(Request $request)
    {
        try{

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
           
            return  "ok";
            
        }catch(\Exception $erro){

            return 'erro';
        }        
    }

    Public function viewReset(){
        $user = Auth::user();
        return view('reset', $user);
    }

    Public function settings(){  
        $user = Auth::user();               
        return view("settings", $user);              
    }

    Public function updateAvatar(Request $request){
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time().'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('uploads/avatars/'.$filename ));
            $user = Auth::user();
            $user->avatar = $filename;
            $user->save();
        }
        return view("settings", $user); 
    }

    public function update(UpdateAccount $request){
        $usuario = Auth::user(); // resgata o usuario

        $usuario->username = Request::input('username'); // pega o valor do input username
        $usuario->email = Request::input('email'); // pega o valor do input email

        if ( ! Request::input('password') == '') // verifica se a senha foi alterada
        {
            $user->password = bcrypt(Request::input('password')); // muda a senha do seu usuario já criptografada pela função bcrypt
        }

        $user->save(); // salva o usuario alterado =)

        Flash::message('Atualizado com sucesso!');
        return Redirect::to("home"); // redireciona pra rota que você achar melhor =)
    }
}
