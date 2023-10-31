<?php
 namespace app\controller;

  class SiteController
  {
   
      //CADA MÉTODO A BAIXO ESTÃO PEGANDO SUAS RESPEQUITIVAS PÁGINAS NO DIRETORIO VIEL
        public function home()
        {
           include_once "app/view/dashboard.php"; //Aqui dentro agora está a página dashboard
        }

        public function sobre()
        {
           include_once "app/view/sobre.php"; //Aqui dentro agora está a página sobre
        }

        public function contato()
        {
           include_once "app/view/contato.php"; //Aqui dentro agora está a página contato
        }
 
      //PÁGINA DE ERRO: 404
        public function paginaErro()
        {
           echo "<h3>Erro 404: Página não encontrada<h3><br>";
        }

      
    
   }
