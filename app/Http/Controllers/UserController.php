<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;

class UserController extends Controller
{
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
           // if ($user->avatar != 'defalt.jpg') {
           //     Image::delete('uploads/avatars/'.$user->avatar);
            //}    
            $user->avatar = $filename;
            $user->save();
        }
        //return view("settings", $user);
        return "ok" ;
    }

    public function saveBase64ToImage($image) {
        
        $path = base_path('uploads/avatars/');
        //$base = $_REQUEST['image'];
        $base = $image;
        $binary = base64_decode($base);
        //$binary = base64_decode(urldecode($base));
        header('Content-Type: bitmap; charset=utf-8');

        $f = finfo_open();
        $mime_type = finfo_buffer($f, $binary, FILEINFO_MIME_TYPE);
        $mime_type = str_ireplace('image/', '', $mime_type);

        $filename = md5(\Carbon\Carbon::now()) . '.' . $mime_type;
        $file = fopen($path . $filename, 'wb');
        if (fwrite($file, $binary)) {
            return $filename;
        } else {
            return FALSE;
        }
        fclose($file);
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
