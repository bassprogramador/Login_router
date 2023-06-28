<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Login</h1>
        <!--<p>Digite os dados de acesso nos campos a baixo</p>-->
        <?php
          //VERIFICANDO SE A SESSÃO EXISTE, EXISTIR EXIBE A MENSAGEM
            if(isset($_SESSION['msg']))
            {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
        ?>
        <form action="<?php echo URL_BASE . "login/recebeDadosLogin/" ?>" method ="POST">
            
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Digite o email" required >
            <br>
            <br>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Digite a senha" required>
            <br>
            <br>
            <input type="submit" name="send_Login" value="Enviar">

        </form>
    </div>
    <div>
      <p>Ainda não tem uma conta?</p>  
      <a href="<?php echo URL_BASE . "login/criarConta/" ?>">Cadastrar</a> - 
      <a href="<?php echo URL_BASE . "login/paginaRecuperarSenha" ?>">Esqueceu a senha?</a>
    </div>
    
    

</body>
</html>