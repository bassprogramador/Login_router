<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>atualizar Senha</title>
</head>
<body>
    <div>
        <h1>Redefinir senha</h1>
      
        <form action="<?php echo URL_BASE . "login/dadosAtualizarSenha/" ?>" method ="POST">
            <?php foreach($result as $id ) : ?>
              <label for="senha">Nova senha</label>
              <input type="password" id="senha" name="nova_senha" placeholder="Digite aqui a nova senha" required >
              <br><br>
             <!-- <label for="senha">Id</label>-->
              <input type="hidden" name ="id" value="<?php echo $result['id']; ?>">
              <br><br>
              <input type="submit" name="send_recuperarSenha" value="Atualizar">
            <?php endforeach ?>
        </form>
        <p>Faça o login - <a href="<?php echo URL_BASE . "login/home/" ?>">Login</a> </p>  
    </div>
</body>
</html>