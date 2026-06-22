<?php

function tieneAcceso($modulo){

    if(!isset($_SESSION['accesos'])){
        return false;
    }

    $accesos = preg_split('/[;,]/', $_SESSION['accesos']);
    $accesos = array_map('trim', $accesos);

    return in_array($modulo, $accesos);
}

?>