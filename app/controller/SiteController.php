<?php
 namespace app\controller;

  class SiteController
  {
   
      //CADA MÉTODO A BAIXO ESTÃO PEGANDO SUAS RESPEQUITIVAS PÁGINAS NO DIRETORIO VIEL
        public function home()
        {
           //Agora página dashboard está dentro do método home
             include_once "app/view/dashboard.php"; 
        }

        public function sobre()
        {
           //Agora página sobre está dentro do método sobre
             include_once "app/view/sobre.php"; 
        }

        public function contato()
        {
          //Agora página contato está dentro do método contato
            include_once "app/view/contato.php"; 
        }
 
      //PÁGINA DE ERRO: 404
        public function paginaErro()
        {
           echo "<h3>Erro 404: Página não encontrada<h3><br>";
        }

      
    
   }
