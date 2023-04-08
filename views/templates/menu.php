<div class="barra">
    <figure class="barra__figure figure">
        <a class="barra__a--figure" href="#" data-cy="boton-menu">
            <img src="/build/img/sistema/menu.svg" width="30px" height="32px" alt="Icono de menú" id="iconoMenu" />
        </a>
    </figure>
</div>

<nav class="nav">

    <a class="nav__a" href="#" id="cerrar"><span>X</span></a>
    <a class="nav__a" data-id="1" data-estado="" href="/usuario">
        <img src="/build/img/sistema/panelusuario-bl.svg" width="30px" height="30px"/>
        <div class="usuario">
            <p><?php echo $_SESSION['nombre'];?></p>
            <p><?php echo $_SESSION['tipoUsuario'] == 'A' ? '(Administrador)' : '(Usuario)';?></p>
        </div>
    </a>

    <a class="nav__a" data-id="2" data-estado="" href="/venta"><img src="/build/img/sistema/panelventas-bl.svg" width="30px" height="30px"/><span>Ventas</span></a>
    <a class="nav__a" data-id="3" data-estado="" href="/compra"><img src="/build/img/sistema/panelcompras-bl.svg" width="30px" height="30px"/><span>Compras</span></a>
    <a class="nav__a" data-id="4" data-estado="" href="#" data-cy="articulos">
        <img src="/build/img/sistema/panelproductos-bl.svg" width="30px" height="30px" class=" "/>
        <span class="">Artículos</span>
        <span class="hat ">&#710</span>
    </a>
    
    <div class="subnav ocultar" data-parent="4">
        <a href="/producto" class="subnav__a ocultar" data-subId="1" data-estado="" data-cy="producto">
            <img src="/build/img/sistema/panelproductos-bl.svg" class="subnav__img" alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Productos</span>
        </a>
        <a href="/categoria" class="subnav__a ocultar" data-subId="2" data-estado="">
            <img src="/build/img/sistema/panelcategorias-bl.svg" class="subnav__img"  alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Categorías</span>
        </a>
        <a href="/marca" class="subnav__a ocultar" data-subId="3" data-estado="">
            <img src="/build/img/sistema/panelmarcas-bl.svg" class="subnav__img" alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Marcas</span>
        </a>
        <a href="/ubicacion" class="subnav__a ocultar" data-subId="4" data-estado="">
            <img src="/build/img/sistema/panelubicacion-bl.svg" class="subnav__img" alt="Imagen icono" width="15px" height="20px">
            <span class="subnav__span">Ubicación</span>
        </a>
    </div>

    <a class="nav__a" data-id="5" data-estado="" href="/cliente"><img src="/build/img/sistema/panelclientes-bl.svg" width="30px" height="30px"/><span>Clientes</span></a>
    <a class="nav__a" data-id="6" data-estado="" href="/proveedor"><img src="/build/img/sistema/panelproveedores-bl.svg" width="30px" height="30px"/><span>Proveedores</span></a>
    <a class="nav__a" data-id="7" data-estado="" href="/notificacion"><img src="/build/img/sistema/panelnotificaciones-bl.svg" width="30px" height="30px"/><span>Notificaciones</span></a>
    <a class="nav__a" data-id="8" data-estado="" href="#"><img src="/build/img/sistema/panelconfiguracion-bl.svg" width="30px" height="30px"/><span>Configuración</span><span class="hat ">&#710</span></a>
    <div class="subnav ocultar" data-parent="8">
        <a href="/parametro" class="subnav__a ocultar" data-subId="1" data-estado="">
            <img src="/build/img/sistema/panelparametros-bl.svg" class="subnav__img" alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Parámetros</span>
        </a>
        <a href="/metodo-pago" class="subnav__a ocultar" data-subId="2" data-estado="">
            <img src="/build/img/sistema/panelpago-bl.svg" class="subnav__img"  alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Métodos de pago</span>
        </a>
        <a href="/tabla" class="subnav__a ocultar" data-subId="3" data-estado="">
            <img src="/build/img/sistema/paneltabla-bl.svg" class="subnav__img" alt="Imagen icono" width="20px" height="20px">
            <span class="subnav__span">Tablas</span>
        </a>
    </div>
    <a class="nav__a" data-id="9" data-estado="" href="/cerrar-sesion"><img src="/build/img/sistema/panelcerrarsesion-bl.svg" width="30px" height="30px"/><span>Cerrar sesión</span></a>
</nav>

<?php

    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/menu.js"></script>';
    }else{
        $script = '<script src="/build/js/panel/menu.js"></script>';
    }

?>