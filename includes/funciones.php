<?php

define('CARPETA_LOGO', $_SERVER['DOCUMENT_ROOT'] . '/build/img/logo/');
define('CARPETA_PRODUCTOS', $_SERVER['DOCUMENT_ROOT'] . '/build/img/productos/');
define('CARPETA_COMPRAS', $_SERVER['DOCUMENT_ROOT'] . '/build/img/compras/');

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Verificar tipo de dato para mostrar en formulario
function tipoAlerta($alertas, $tipoError, $campo){
    if (isset($alertas[$tipoError][$campo])) {
        if (gettype($alertas[$tipoError][$campo]) == 'string'){
            return s($alertas[$tipoError][$campo]);

        }elseif(gettype($alertas[$tipoError][$campo]) == 'array'){
            return s($alertas[$tipoError][$campo][0]);                                        
        }
    }
}

function isAuth(){
    if($_SESSION['tipoUsuario'] !== 'U'){
        header('Location: /');
    }
}

function isAdmin(){
    if($_SESSION['tipoUsuario'] !== 'A'){
        header('Location: /');
    }
}
