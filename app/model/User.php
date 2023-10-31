<?php 
    namespace app\model;
    use app\model\Conexao;

  //INICIALIZANDO A SESSÃO E LIMPANDO O BUFFER DE SAÍDA PARA NÃO DA ERRO NO REDIRECIONAMENTO
   // session_start();
    ob_start();  
  
  //IMPORTAR AS CLASSES
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    
    class User extends Conexao
    { 
       
      /************************************************************************************ */
      /*                                  LOGAR USUÁRIO                                     */
      /************************************************************************************ */

      //QUERY - CONSULTAR SE O USÚARIO TEM PERMISSÃO DE ACESSO
        public function getUsuario($email, $senha)
        {
          $conect = $this->banco(); //chamei o método banco da classe Conexão
          $query_sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
          $stmt = $conect->prepare($query_sql);
          $stmt->bindParam(':email' , $email);
          $stmt->execute();

            if($stmt->rowCount() === 1) //Se número de linhas for idêntico a 1
            { 
                //fetch -> Retorna um array associativo de uma única linha de registro 
                $usuario = $stmt->fetch(\PDO::FETCH_ASSOC); 
                  
                /*A função password_verify()verifica se a senha passada no primeiro parâmetro(senha de login)
                  é igual ao hash que foi passado no segundo parâmetro(senha cadastrada no banco de dados) */
                  if(password_verify($senha, $usuario['senha']))
                  {
                    /*Se for! vou criar duas variáveis de sessão com o nome: 'id' e 'nome' 
                      e atribuir a chave 'id' e a chave 'nome' do array fetch atribuido à variavel $usuario
                      e essas duas variáveis vou utilizar em todo meu código */
                      
                      $_SESSION['id'] = $usuario['id'];          
                      $_SESSION['nome'] = $usuario['nome'];
                    
                      //REDIRECIONANDO PARA A PÁGINA DASHBOARD
                        header("location:".URL_BASE. "site/home");    
                  }
                  else
                  {
                      $_SESSION['msg'] = '<p style= color:#ff0000> Erro: Usuário ou Senha inválido </p>';
                      header("location:".URL_BASE. "site/home");
                  }
            }
            else
            {
                $_SESSION['msg'] = '<p style= color:#ff0000> Erro: Usuário ou Senha inválido </p>';
                header("location:".URL_BASE. "site/home");
            }

            //LIMPAR E FECHAR CONEXÃO
              unset($conect);
        }

      /************************************************************************************ */
      /*                                CADASTRAR USUÁRIO                                   */
      /************************************************************************************ */
       
      //QUERY PARA INSERIR USUÁRIO NO BANCO
        public function inserir($nome, $email, $senha, $chaveRecuperarSenha)
        { 
          
          //PRIMEIRO VERIFICAR SE O EMAIL JÁ FOI CADASTRADO
            $conect = $this->banco(); 
            $query_sql = "SELECT * FROM usuario WHERE email =:email LIMIT 1";
            $stmt = $conect->prepare($query_sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
             
              if($stmt->rowCount() > 0)
              {
                $_SESSION['msg'] = "<p style= color:#ff0000>Já existe um usuário cadastrado com este email! </p>";
              }
              else
              {
                //CADASTRANDO NO BANCO O USUÁRIO
                  $sql = "INSERT INTO usuario value (default,:nome, :email, :senha, :chaveRecuperarSenha)";
                  $stmt = $conect->prepare($sql);
                  $stmt->bindParam(':nome',$nome);
                  $stmt->bindParam(':email',$email);
                  $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
                  $stmt->bindParam(':senha', $senha_criptografada);
                  $stmt->bindParam(':chaveRecuperarSenha', $chaveRecuperarSenha );
                  $stmt->execute();

                    //Se quantidade de linhas confere com a quantidade de valores enviados: então é TRUE
                      if($stmt->rowCount() == TRUE)
                      {
                        $_SESSION['msg'] = "<p style= color:green> Usuário cadastrado com sucesso! </p>";
                      }
                      else
                      {
                        $_SESSION['msg'] = "<p style= color:#ff0000> Erro: algo deu errado tente novamente! </p>";
                      }

                    //LIMPAR E FECHAR CONEXÃO
                      unset($conect);
              }
        }
       
      
      /************************************************************************************ */
      /*                           RECUPERAR A SENHA DO USUÁRIO                             */
      /************************************************************************************ */
      
        public function getVerificarUsuario($email)
        {
         
          //VERIFICANDO SE O EMAIL DO USUÁRIO EXISTE NO BANCO
            $conect = $this->banco(); 
            $query_sql = "SELECT id, nome, email FROM usuario WHERE email =:email LIMIT 1";
            $stmt = $conect->prepare($query_sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
             
              if($stmt->rowCount() == 1)
              {
                // LER OS REGISTROS DO BANCO 
                  $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
                  return $usuario;
              }
              else
              {
                  $_SESSION['msg'] = "<p style= color:#ff0000> Erro: Usuário não encontrado! </p>";

                  //REDIRECIONANDO PARA A PÁGINA RECUPERAR SENHA
                    header("location:".URL_BASE. "login/home");   
                    exit(); 
              }
              
              //LIMPAR E FECHAR CONEXÃO
                 unset($conect);
        
        }

      /***********************************************************************************************/
      /*               INSERIR A CHAVE NO BANCO E ENVIAR PARA O EMAIL DO USUÁRIO                     */
      /***********************************************************************************************/
         
        public function editarSenha($id , $nome, $chaveRecuperarSenha )
        {
            $conect = $this->banco();  
            $query_sql = "UPDATE usuario SET chaveRecuperarSenha = :chaveRecuperarSenha WHERE id=:id";
            $stmt = $conect->prepare($query_sql);
            $stmt->bindParam(':chaveRecuperarSenha', $chaveRecuperarSenha);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if($stmt == TRUE)
            {
                $link = "http://localhost/login_router/login/paginaAtualizarSenha?chave=$chaveRecuperarSenha";
                
               
         
                //Criar a instância 
                $mail = new PHPMailer(true);

                try 
                {
                    //Configurações do Servidor
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;             //Imprimir erro do debug
                    $mail->CharSet = 'UTF-8';                            //Envio de email com caracteres especiais
                    $mail->isSMTP();                                     //Definir para usar SMTP
                    $mail->Host       = 'sandbox.smtp.mailtrap.io';      //Servidor de envio do email
                    $mail->SMTPAuth   = true;                            //Indica que é necessário autenticar
                    $mail->Username   = 'dd7cad2840a9a5';                //Email do usuário que vai receber o email
                    $mail->Password   = 'e45456e83803c9';                //Senha do email para enviar o email
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  //Ativar a criptografia
                    $mail->Port       = 465;                             //Porta para enviar o email

                    //Configuração da Mensagem dos Destinatários
                    $mail->setFrom('rosilene@example.net', 'Rosilene');   //Email do remetente
                    $mail->addAddress('sergio@example.net', 'Sérgio');    //Email de destino
                    
                    //Conteúdo
                    $mail->isHTML(true);                                  //Definir formato de email para HTML
                    
                    //Título do email
                    $mail->Subject = 'Recuperar senha';   
                    
                    //Conteúdo do email em formato HTML            
                    $mail->Body    = "Olá" .$nome. "<br><br> Você solicitou alteração de senha.
                    <br><br>Para continuar o processo de recuperação de sua senha, clique no link abaixo ou 
                    cole o endereço no seu navegador: <br><br><a href='" .$link. "'>" .$link . "<a/><br><br>
                    se você não solicitou essa alteração, nenhuma ação é necessária. sua senha permanecerá
                    a mesma até que você ative este código.<br></br>";
                    
                    //conteúdo do email em formato texto
                    $mail->AltBody = "Olá" .$nome. "\n\n Você solicitou alteração de senha.
                    \n\n Para continuar o processo de recuperação de sua senha, clique no link abaixo ou 
                    cole o endereço no seu navegador: \n\n"  .$link. "\n\n\se você não solicitou essa alteração,
                     nenhuma ação é necessária. sua senha permanecerá a mesma até que você ative este código.<br></br>";

                    //enviar o email
                    $mail->send();

                    $_SESSION['msg'] = "<p style= color:green> Enviado email com instruções para recuperação
                    a senha. Acesse a sua caixa de e-mail para recuperar a senha! </p>";
                    
                    //Redirecionar o usuário
                    header("location:".URL_BASE. "login/home");
                    
                    exit();
                
                } 
                catch (Exception $e) 
                {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  //  $_SESSION['msg'] = "<p style= color:#ff0000> Erro: Email não enviado com súcesso! </p>";
                }
            }
            else
            {
                $_SESSION['msg'] = "<p style= color:#ff0000> Erro: Algo deu errado tente novamente! </p>";

                //REDIRECIONANDO PARA A PÁGINA RECUPERAR SENHA
                  header("location:".URL_BASE. "login/home");   
                  exit(); 
            }
             
        }
         
      /*************************************************************************************/
      /*                      VERIFICANDO SE EXISTE A CHAVE NO BANCO                       */
      /*************************************************************************************/
        
        public function verificandoChave($chaveRecuperarSenha)
        {
        
          // VERIFICANDO SE A CHAVE EXISTE NO BANCO 
             $conect = $this->banco();
             $query_sql = "SELECT id FROM usuario WHERE chaveRecuperarSenha = :chaveRecuperarSenha LIMIT 1";
             $stmt = $conect->prepare($query_sql);
             $stmt->bindParam(':chaveRecuperarSenha' , $chaveRecuperarSenha);
             $stmt->execute();

                if($stmt->rowCount() === 1)
                { 
                    //LENDO OS REGISTROS NO BANCO E RETORNANDO 
                      $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);//array associativo
                      return $usuario;
                      
                }
                else
                {
                      $_SESSION['msg'] = '<p style= color:#ff0000> Erro: Email não cadastrado</p>';
                      header("location:".URL_BASE. "login/home");
                      
                      //LIMPAR E FECHAR CONEXÃO
                        unset($conect);
                      //PAUSA O PROCESSAMENTO
                        exit();
                      
                }
                
                //LIMPAR E FECHAR CONEXÃO
                  unset($conect);
            
        }

      /*************************************************************************************/
      /*                               ATUALIZANDO A SENHA                                 */
      /*************************************************************************************/
        
        public function atualizarSenha($senha, $chaveRecuperarSenha, $id)
        {
          $conect = $this->banco(); 
          $query_sql = "UPDATE usuario SET senha = :senha, chaveRecuperarSenha =:chaveRecuperarSenha WHERE id=:id LIMIT 1";
          $stmt = $conect->prepare($query_sql);
          $stmt->bindParam(':senha', $senha);
          $stmt->bindParam(':chaveRecuperarSenha', $chaveRecuperarSenha);
          $stmt->bindParam(':id', $id);
          $stmt->execute();

              if($stmt == TRUE)
              { 
                    $_SESSION['msg'] = '<p style= color:green> Senha atualizada com sucesso! </p>';
                    header("location:".URL_BASE. "login/home");
                
                    //LIMPAR E FECHAR CONEXÃO
                      unset($conect);         
              }
              else
              {
                    $_SESSION['msg'] = '<p style= color:#ff0000> Erro: Tente novamente </p>';
                    header("location:".URL_BASE. "login/home");
                   
                    //LIMPAR E FECHAR CONEXÃO
                      unset($conect);
              }
            
        }
        
    }
            
                  
            
   
