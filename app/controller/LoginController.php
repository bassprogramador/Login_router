<?php
 namespace app\controller;
 use app\model\User;

 //INICIALIZANDO A SESSÃO E LIMPANDO O BUFFER DE SAÍDA PARA NÃO DA ERRO NO REDIRECIONAMENTO
   session_start();
   ob_start();  
 
 class LoginController
 {
   
   /***************************************************************************************/
   /*                                  FAZENDO LOGIN                                      */
   /***************************************************************************************/
   
   // CHAMANDO A PÁGINA DE LOGIN
      public function home()
      {
         include_once "app/view/login.php";
      }
   
   // RECEBENDO OS DADOS VINDO DO FORMULÁRIO DE LOGIN PARA SEREM PROCESSADOS
      public function recebeDadosLogin()
      {
         if(isset($_POST['send_Login']))
         {
               $user = new User();
               $email = $_POST['email'];
               $senha = $_POST['senha'];
               $user->getUsuario($email, $senha);
         }    
      }
   
   /******************************************************************************************/
   /*                              CRIANDO UMA CONTA DE USUÁRIO                              */
   /******************************************************************************************/

   // CHAMANDO A PÁGINA CRIAR CONTA 
      public function criarConta()
      {
         include_once "app/view/criarconta.php";
         
      }
   
   // RECEBENDO OS DADOS VINDO DO FORMULÁRIO CRIAR CONTA PARA SEREM PROCESSADOS
      public function recebeDadoscadastrar()
      {
         if(isset($_POST['send_cadastrar']))
         {
            $user = new User();
            $nome = filter_input(INPUT_POST,  'nome' , FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = filter_input(INPUT_POST, 'senha', FILTER_VALIDATE_INT);
            $confirmarSenha = filter_input(INPUT_POST, 'confirmarSenha', FILTER_VALIDATE_INT);
            $chaveRecuperarSenha = NULL;
            
                
              if($senha === $confirmarSenha)
              {
                  $user->inserir($nome, $email, $senha, $chaveRecuperarSenha);
                  header("location:".URL_BASE. "login/criarConta/");
              }
              else
              {
                  $_SESSION['msg'] = '<p style= color:green> Erro: Senha não confere!</p>';
                  header("location:".URL_BASE. "login/criarConta/");
              }
   
         }
         
      }
   
   /*****************************************************************************************/
   /*                                  FAZENDO LOGOUT                                       */
   /*****************************************************************************************/
   
   // SAINDO DO SISTEMA
      public function Logout()
      {
         unset($_SESSION['id'], $_SESSION['nome']); 
         $_SESSION['msg'] = '<p style= color:green> Erro: Usuário deslogado com sucesso!</p>';
         header("location:".URL_BASE. "site/home");
      }

   /*****************************************************************************************/
   /*                                RECUPERANDO A SENHA                                    */
   /*****************************************************************************************/

   // CHAMANDO A PÁGINA RECUPERAR SENHA 
      public function paginaRecuperarSenha()
      {
         include_once "app/view/recuperarSenha.php";
      }

   // RECEBENDO O DADO VINDO DO INPUT RECUPERAR SENHA
      public function recebeDadosVerificarUsuario()
     {
         if(isset($_POST['send_recuperarSenha']))
         {
               $user = new User();//instâncie a classe
               $email = $_POST['email']; 
               $result = $user->getVerificarUsuario($email);
                
               // Transformei as chaves do array em variável
                  extract($result);
               
               // Gerando a chave para editar no banco e enviar para o email
                  $chaveRecuperarSenha = password_hash($id . $email, PASSWORD_DEFAULT);
                  $user->editarSenha($id , $nome, $chaveRecuperarSenha );
         }    
      }

   /**********************************************************************************************/
   /*               RECEBENDO A CHAVE DO EMAIL E VERIFICANDO SE EXISTE NO BANCO                  */
   /**********************************************************************************************/
   
   // CHAMANDO A PÁGINA ATUALIZAR SENHA 
      public function paginaAtualizarSenha()
      {
         // pegando a chave na URL 
            $chaveRecuperarSenha = filter_input(INPUT_GET,  'chave' , FILTER_SANITIZE_SPECIAL_CHARS);
            
            if(isset($chaveRecuperarSenha))
            {
               $user = new User();//instâncie a classe
               $result = $user->verificandoChave($chaveRecuperarSenha);
            }
            else
            {
               $_SESSION['msg'] = '<p style= color:#ff0000> Erro: Link inválido!!</p>';
               header("location:".URL_BASE. "login/home");
               exit();//para o processamento

            }
            include_once "app/view/atualizarSenha.php";
      }

   /**********************************************************************************************/
   /*       RECEBENO OS DADOS DO FORMULARIO ( atualizarSenha.php) E ATUALIZANDO NO BANCO         */
   /**********************************************************************************************/


      public function dadosAtualizarSenha()
      {
         $senha = filter_input(INPUT_POST,  'nova_senha' , FILTER_SANITIZE_SPECIAL_CHARS);
         $id = filter_input(INPUT_POST,  'id' , FILTER_SANITIZE_SPECIAL_CHARS);
         $chaveRecuperarSenha = NULL;

            if(isset($_POST['send_recuperarSenha']))
            {
               $user = new User();//instâncie a classe
               $senha = password_hash($senha, PASSWORD_DEFAULT);
               $edit = $user->atualizarSenha($senha, $chaveRecuperarSenha, $id);
            }
           
      }
   
   /**********************************************************************************************/
   /*            EXIBE A PÁGINA DE ERRO QUANDO NÃO ENCONTRAR O CONTROLLER E O MÉTODO             */
   /**********************************************************************************************/
     
   // CHAMANDO A PÁGINA ERRO PARA SER EXIBIDA 
      public function paginaErro()
      {
         echo "Erro: Página não encontrada";
            
      }
            
         
   }