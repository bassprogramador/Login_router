<?php
 
 include_once "config/config.php";
 include_once "vendor/autoload.php";
 include_once "core/rota.php";


 

 if("http://localhost/login_router/" == TRUE)
 {
     $rota = new Rota();
 }
 else
 {
    echo "cabecalho";
    $rota = new Rota();
    echo "rodapé";
 }
 
 
 
 
 
 

 
 