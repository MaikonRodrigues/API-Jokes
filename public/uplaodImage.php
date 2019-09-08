<?php

    function conectar(){
        $host="localhost";
        $bd = "piadas";
        $user = "root";
        $senha = "";

        $con = new mysqli($host, $user, $senha, $bd);
        return $con;
    }

    $conexao = conectar();
       
    $nome = md5($_FILES['imagem']['name'].rand(1,999)).'.jpg';         
         
    $imagem = $_POST["imagem"];
    $user_id  = $_POST["user_id"]; 
    
         
    $url =  "$nome";
    $path = "uploads/avatars/$nome";

    file_put_contents($path, base64_decode($imagem));          

    $bytesArquivo = base64_decode($imagem);

    
    $result = mysqli_query($conexao, "SELECT avatar FROM users WHERE users.id = '{$user_id}'"); 
    $after = mysqli_fetch_assoc($result); 
    
    if ($after['avatar'] != 'default.png') {
        unlink('uploads/avatars/'.$after['avatar']);
    }

    $inserir = "UPDATE users SET avatar = '{$url}' WHERE users.id = '{$user_id}' ";        
    $resultado_inserir = mysqli_query($conexao, $inserir);
        
    if($resultado_inserir){
        echo "ok";
    }
        
    mysqli_close($conexao);
?>

