<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h1>Cadastrando Usuário</h1>
        <?php
          //VERIFICANDO SE A SESSÃO EXISTE, EXISTIR EXIBE A MENSAGEM
            if(isset($_SESSION['msg']))
            {
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
        ?>
        <form action="<?php echo URL_BASE . "login/recebeDadoscadastrar/" ?>" method ="POST">
            
            <label for="nome">Nome</label><br>
            <input type="text" id="nome" name="nome" placeholder="Digite o nome" required >
            <br>
            <br>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="Digite o email" required >
            <br>
            <br>
            <label for="senha">Senha</label><br>
            <input type="password" id="senha" name="senha" placeholder="Digite a senha" required>
            <br>
            <br>
            <label for="confirmarSenha">Confirmar Senha</label><br>
            <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Confirme a senha" required>
            <br>
            <br>
            <input type="submit" name="send_cadastrar" value="Registrar">

        </form>
    </div>
    <div>
      <p>Faça o login - <a href="<?php echo URL_BASE . "login/home/" ?>">Login</a> </p>  
    </div>
</body>
</html>