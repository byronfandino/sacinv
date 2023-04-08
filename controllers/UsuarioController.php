<?php

namespace Controllers;

use Model\Usuario;

class UsuarioController{

    //Api de nicknames y correos electronicos
    public static function listarNicknames(){
        
        $usuarios=[];
        $emails=[];

        //obtenemos todos los usuarios del sistema para acceder a los nickname e emails de cada uno 
        $usuarios_sistema=Usuario::all('Us_NickName');
        
        //Creamos dos arreglos, uno de emails y otro de NickNames, para evitar pasar información innecesaria
        foreach($usuarios_sistema as $usuario){
            $usuarios[] = $usuario->Us_NickName;
            $emails[] = $usuario->Us_Email;
        }
        echo json_encode([$usuarios, $emails]);
    }
}

?>