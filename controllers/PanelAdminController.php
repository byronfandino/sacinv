<?php

namespace Controllers;

use MVC\Router;

class PanelAdminController{    
    public static function index(Router $router){
        
        if(!isset($_SESSION['nombre'])){
            header('Location: /');
        }

        isAdmin();

        $router->renderPanel('panel/panel-admin');
    } 
}

?>