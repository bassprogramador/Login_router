<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>
    <div>
        <h1>Recuperar senha</h1>
        <?php
          //VERIFICANDO SE A SESSÃO EXISTE, EXISTIR EXIBE A MENSAGEM
            if(isset($_SESSION['msg']))
            {
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
        ?>
        <form action="<?php echo URL_BASE . "login/recebeDadosVerificarUsuario/" ?>" method ="POST">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Digite o email cadastrado" required >
            <br><br>
            <input type="submit" name="send_recuperarSenha" value="Recuperar">

        </form>
    </div>
    <div>
        <p>Lembrou a senha? <a href="<?php echo URL_BASE . "login/home/" ?>"> Clique aqui </a> para logar</p>
        
    </div>
</body>
</html>