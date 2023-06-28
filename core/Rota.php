<?php
 
class Rota{
    
    // ATRIBUTOS
       public $controller;
       public $metodo;
       public $parametro = [];

    public function __construct()
    {
       // CHAMANDO O MÉTODO url   
          $uri = $this->url();
       
       // INSTÂNCIANDO UM OBJETO APARTE DAS CLASSES DO CONTROLLER
          $classe = $this->getController();
          $objeto = new $classe;
       
       // EXECUTANDO OS MÉTODOS E OS PARÂMETROS DESSE OBJETO
          call_user_func_array(array($objeto, $this->getMetodo()), $this->parametro);
       
    }
    public function url()
    {
     //RECEBENDO A URL DO .htaccess
       $url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
          
          // VERIFICANDO SE NÃO ESTÁ VAZIA A URL
          if(!empty($url))  #if($url != "")
          {
             $uri = explode("/", $url);

             //CONTROLLER
             $this->controller = $uri[0];
                unset($uri[0]);
             
             //MÉTODO
             if(isset($uri[1]))
             {
                   $this->metodo = $uri[1];
                   unset($uri[1]);
             }

             //PARÂMETROS
             if(isset($uri))
             {
                   $this->parametro = array_values($uri);
             }
          }
          else
          {
             //CONTROLLER E MÉTODO PADRÃO
               $this->controller = "login";
               $this->metodo =     "home";
          } 
    }
    //PEGANDO OS ATRIBUTOS QUE CONTÉM AS URI's VINDO DA URL
    public function getController()
    {
       // VERIFICANO SE O ARQUIVO QUE CONTÉM A CLASSE EXISTE
          if(file_exists("app/controller/".ucfirst($this->controller)."Controller.php"))
          {
             // RETORNANDO A CLASSE   
                return "app\\controller\\".ucfirst($this->controller)."Controller";
          }
          else
          {
             // RETORNA O CONTROLLER PADRÃO 'Site'
                return "app\\controller\\ErroController";
          }
    }
    public function getMetodo()
    {
       // VERIFICANDO SE O MÉTODO DA CLASSE REFERENCIADA EXISTE 
          if(method_exists("app\\controller\\".ucfirst($this->controller)."Controller", $this->metodo))
          {
                return $this->metodo;
          }
          else
          {
              return $this->metodo = "paginaErro";
          }
    }
    public function getParametro()
    {
       if(isset($this->parametro))
       { 
          return $this->parametro;   
       }
    }
 }
 
 
 