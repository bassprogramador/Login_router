<?php include_once "app/view/cabecalho.php"; ?>

<div>
    
        <?php

        //VERIFICANDO SE O USUÁRIO ESTÁ LOGADO, SE NÃO, REDIRECIONA PARA A PÁGINA DE LOGIN
        session_start();
        ob_start();
        if(!isset($_SESSION['id']) AND (!isset($_SESSION['nome'])))
        {
          header("location:".URL_BASE. "login/home");
          exit;
        }
          
        
          echo "<h3>Sou a página contato</3>";
        ?>
</div>

<?php include_once "app/view/rodape.php"; ?>
