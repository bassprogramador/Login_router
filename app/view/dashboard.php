<?php include_once "app/view/cabecalho.php"; 

      //VERIFICANDO SE O USUÁRIO ESTÁ LOGADO, SE NÃO, REDIRECIONA PARA A PÁGINA DE LOGIN
        session_start();
        ob_start();
        if(!isset($_SESSION['id']) AND (!isset($_SESSION['nome'])))
        {
          header("location:".URL_BASE. "login/home");
          exit;
        }
?>
      
    <main>
      <h1>Dashboard paínel privado</h1>
      <p>Seja bem vindo <?php echo $_SESSION['nome'] ?>!</p>
    </main>
    
    <!--LINK PARA SAIR-->
    <a href="<?php echo URL_BASE."login/Logout" ?>">Sair</a>

<?php include_once "app/view/rodape.php"; ?>