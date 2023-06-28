<?php
 namespace app\controller;

  class SiteController
  {
   
      //CHAMANDO A PÁGINA DE DASHBOARD
        public function home()
        {
           include_once "app/view/dashboard.php";
        }

        public function sobre()
        {
           include_once "app/view/sobre.php";
        }

        public function contato()
        {
           include_once "app/view/contato.php";
        }
 
      //PÁGINA DE ERRO: 404
        public function paginaErro()
        {
           echo "<h3>Erro 404: Página não encontrada<h3><br>";
        }

      
    
   }