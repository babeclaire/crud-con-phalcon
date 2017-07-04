<?php
use \Phalcon\Paginator\Adapter\QueryBuilder as PaginacionBuilder;
class AppController extends ControllerBase
{
    public function indexAction()
    {
    	 $pubs = $this->modelsManager->createBuilder()
	    ->from('Pubs')
	    ->orderBy('id');

		$paginator = new PaginacionBuilder(array(
		    "builder" => $pubs,
		    "limit"=> 5,
		    "page" => $this->request->getQuery('page', 'int')
		));
 		
        //pasamos el objeto a la vista con el nombre de $page
        $this->view->page = $paginator->getPaginate();
    }
public function addAction()
    {
    	//deshabilitamos la vista para peticiones ajax
        // $this->view->disable();
        //si es una petición post
        if($this->request->isPost() == true) 
        {
            //si es una petición ajax
            if($this->request->isAjax() == true) 
            {
            	//si existe el token del formulario y es correcto(evita csrf)
            	if($this->security->checkToken()) 
            	{
		        	$pub = new Pubs();
		        	//  para entrar cada columna de la tabla
		        	$pub->cod_pub = $this->request->getPost('cod_pub', array('striptags', 'trim'));
	                $pub->title = $this->request->getPost('title', array('striptags', 'trim'));
	                $pub->content = $this->request->getPost('content', array('striptags', 'trim'));
	                //si el post se guarda correctamente
	                if($pub->save())
	                {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"success"
				        ));
				        //devolvemos un 200, todo ha ido bien
				        $this->response->setStatusCode(200, "OK");
	                }
	                else
	                {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"error"
				        )); 
				        //devolvemos un 500, error
				        $this->response->setStatusCode(500, "Internal Server Error");
	                }
				    $this->response->send();
	            }
            }
        }
    }
    public function editAction()
    {
    	//deshabilitamos la vista para peticiones ajax
        $this->view->disable();
 
        //si es una petición post
        if($this->request->isPost() == true) 
        {
            //si es una petición ajax
            if($this->request->isAjax() == true) 
            {
            	//si existe el token del formulario y es correcto(evita csrf)
            	if($this->security->checkToken()) 
            	{
            		$parameters = array(
			            "id" => $this->request->getPost('id')
			        );

		        	$pub = Pubs::findFirst(array(
			            "id = :id:",
			            "bind" => $parameters
			        ));
			        $pub->cod_pub = $this->request->getPost('cod_pub', array('striptags', 'trim'));
	                $pub->title = $this->request->getPost('title', array('striptags', 'trim'));
	                $pub->content = $this->request->getPost('content', array('striptags', 'trim'));
	                //si el post se actualiza correctamente
	                if($pub->update())
	                {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"success"
				        ));
				        //devolvemos un 200, todo ha ido bien
				        $this->response->setStatusCode(200, "OK");
	                }
	                else
	                {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"error"
				        )); 
				        //devolvemos un 500, error
				        $this->response->setStatusCode(500, "Internal Server Error");
	                }
				    $this->response->send();
	            }
            }
        }
    }

    public function deleteAction()
    {
    	//deshabilitamos la vista para peticiones ajax
        $this->view->disable();
 
        //si es una petición get
        if($this->request->isGet() == true) 
        {
            //si es una petición ajax
            if($this->request->isAjax() == true) 
            {
	        	$pub = Pubs::findFirst($this->request->get("id"));
				if($pub != false) 
				{
				    if($pub->delete() != false) 
				    {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"success"
				        ));
				        //devolvemos un 200, todo ha ido bien
				        $this->response->setStatusCode(200, "OK");
	                }
	                else
	                {
	                	$this->response->setJsonContent(array(
				            "res"		=>		"error"
				        )); 
				        //devolvemos un 500, error
				        $this->response->setStatusCode(500, "Internal Server Error");
	                }
	            }
			    $this->response->send();
            }
        }
    }

}

    /**
	* @desc - permitimos editar un post
	* @return json
	*/
