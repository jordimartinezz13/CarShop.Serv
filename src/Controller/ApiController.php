<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use App\Entity\Coche;
use App\Entity\Categoria;
use App\Entity\User;
use App\Controller\UserController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class ApiController extends AbstractFOSRestController
{


  /**
  * GET Route annotation.
  * @Get("/api/ultimaID")
  */
  public function getultimaIdCoche()  {

    $repository = $this->getDoctrine()->getRepository(Coche::class);
    // look for *all* Product objects
    $productes = $repository->findAll();

// el camp imatge que hem de subministrar al client es la URL que serveix la imatge
$mayor= $productes[0];
    foreach ($productes as $producte) {
        if($producte->getId() >= $mayor->getId()){
            $mayor=$producte;
        }
    }
    //$mayor->setId($mayor->getId()+1);

    $data = $mayor;//->getId();
    

// construim la resposta HTTP i l'enviem
    $view = $this->view($data, 200);
    $view->setFormat('json');
    return $this->handleView($view);
    return new View("no hay campo existente", Response::HTTP_NOT_FOUND);

}


  /**
  * GET Route annotation.
  * @Get("/api/listCategoria", name="get_categoria")
  */
  public function getCategoria()
  {
      $repository = $this->getDoctrine()->getRepository(Categoria::class);
      // look for *all* Product objects
      $categorias = $repository->findAll();
//print_r ($categorias);
//      exit();
/*oreach ($variable as $key => $value) {
    # code...
}
      $hola = new stdClass();
      $hola->nom=*/
      $data = $categorias;

// construim la resposta HTTP i l'enviem
      $view = $this->view($data, 200);
      $view->setFormat('json');
      return $this->handleView($view);
  }

  /**
  * GET Route annotation.
  * @Get("/api/mostrarUnCoche/{id}", name="get_uncoche")
  */
  public function mostrarUnCoche(int $id)
  {
      $coche = $this->getDoctrine()
          ->getRepository(Coche::class)
          ->find($id);



      if (!$coche) {
          throw $this->createNotFoundException(
              'No product found for id '.$id
          );
      }
      $data = $coche;
      
      // construim la resposta HTTP i l'enviem
            $view = $this->view($data, 200);
            $view->setFormat('json');

            
            return $this->handleView($view);

      // or render a template
      // in the template, print things with {{ product.name }}
      // return $this->render('product/show.html.twig', ['product' => $product]);
  }

  /**
  * GET Route annotation.
  * @Get("/api/mostrarUnaCategoria/{id}", name="get_una_categoria")
  */
  public function mostrarUnaCategoria(int $id)
  {
      $categorias = $this->getDoctrine()
          ->getRepository(Categoria::class)
          ->find($id);



      if (!$categorias) {
          throw $this->createNotFoundException(
              'No product found for id '.$id
          );
      }
      $data = $categorias;
      
      // construim la resposta HTTP i l'enviem
            $view = $this->view($data, 200);
            $view->setFormat('json');

            
            return $this->handleView($view);

      // or render a template
      // in the template, print things with {{ product.name }}
      // return $this->render('product/show.html.twig', ['product' => $product]);
  }
  


  /**
  * POST Route annotation.
  * @Post("/api/crearCoche", name="post_coche")
  */
  public function crearCoche(Request $request)
  {
      try{
    $coche = new Coche();
    //$coche->setId($request->get('id'));
    $coche->setImatge($request->get('imatge'));
    $coche->setNom($request->get('nom'));
    $coche->setPreu($request->get('preu'));

    $categoria = new Categoria();
    $categoria->setId($request->get('categoriaid'));
    $categoria->setNom($request->get('categorianom'));

    $coche->setCategoria($categoria);

    $em = $this->getDoctrine()->getManager();
        $em->merge($coche);//OJO, SI PONES PERSISTANCE CREA UNA CAATEGORIA NUEVA, CREA EN CASCADA
        $em->flush();

        $data = $coche;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        return $this->handleView($view);
    }catch(\Exception $e){
        //return new View("no hay campo existente", 500);
        $view = $this->view($e, 500);
        $view->setFormat('json');
        return $this->handleView($view);
  }
}

/**
  * POST Route annotation.
  * @Post("/api/crearCoche1", name="post_coche1")
  */
  public function crearCoche1(Request $request)
  {
    //{ "id": 0, "imatge": "img121212", "nom": "e", "preu": "3", "categoriaid": 1, "categorianom": null }
    //$hola = '{ "id": 0, "imatge": "img121212", "nom": "e", "preu": "3", "categoriaid": 1, "categorianom": null }';
    $final = json_decode($request->getContent(), true);
    //print_r($final);
    //echo $final['imatge'];
    //exit();
      try{
    $coche = new Coche();
    //$coche->setId($request->get('id'));
    if(isset($final['imatge'])){
        $coche->setImatge("http://localhost/Clase/WebService/symfony/WS_jordi/ws_jordi/img/".$final['imatge'].".png");
    }
    if(isset($final['nom'])){
        $coche->setNom($final['nom']);
    }
    if(isset($final['preu'])){
        $coche->setPreu($final['preu']);
    }
    

    $categoria = new Categoria();
    if(isset($final['categoriaid'])){
        $categoria->setId($final['categoriaid']);
    }
    if(isset($final['categorianom'])){
        $categoria->setNom($final['categorianom']);
    }
    

    $coche->setCategoria($categoria);

    $em = $this->getDoctrine()->getManager();
        $em->merge($coche);//OJO, SI PONES PERSISTANCE CREA UNA CAATEGORIA NUEVA, CREA EN CASCADA
        $em->flush();

        $data = $coche;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        return $this->handleView($view);
    }catch(\Exception $e){
        //return new View("no hay campo existente", 500);
        $view = $this->view($e, 500);
        $view->setFormat('json');
        return $this->handleView($view);
  }
}

/**
  * POST Route annotation.
  * @Post("/api/crearCoche2", name="post_coche2")
  */
  public function crearCoche2(Request $request)
  {
    //{ "id": 0, "imatge": "img121212", "nom": "e", "preu": "3", "categoriaid": 1, "categorianom": null }
    //$hola = '{ "id": 0, "imatge": "img121212", "nom": "e", "preu": "3", "categoriaid": 1, "categorianom": null }';
    //$respuesta = json_decode($request->getContent(), true);
    $final = json_decode($request->get('producto'), true);//$respuesta['producto'];

    //mira el token y el usuario y si tiene permisos
    $response = $this->forward('App\Controller\UserController::fancyAction', [
        'token'  => $request->get('token')
    ]);
    //$userController = new UserController();
    //return $response;//$userController.credencialesToken($request->get('token')));
    if($response->getStatusCode() == 200){
        //var_dump($final);
        //exit;
        //print_r($final);
        //echo $final['imatge'];
        //exit();
        try{
        $coche = new Coche();
        //$coche->setId($request->get('id'));
        if(isset($final['imatge'])){
            $coche->setImatge("http://localhost/Clase/WebService/symfony/WS_jordi/ws_jordi/img/".$final['imatge'].".png");
        }
        if(isset($final['nom'])){
            $coche->setNom($final['nom']);
        }
        if(isset($final['preu'])){
            $coche->setPreu($final['preu']);
        }
        

        $categoria = new Categoria();
        if(isset($final['categoriaid'])){
            $categoria->setId($final['categoriaid']);
        }
        if(isset($final['categorianom'])){
            $categoria->setNom($final['categorianom']);
        }
        

        $coche->setCategoria($categoria);

        $em = $this->getDoctrine()->getManager();
            $em->merge($coche);//OJO, SI PONES PERSISTANCE CREA UNA CAATEGORIA NUEVA, CREA EN CASCADA
            $em->flush();

            $data = $coche;

            // construim la resposta HTTP i l'enviem
            $view = $this->view($data, 200);
            $view->setFormat('json');
            return $this->handleView($view);
        }catch(\Exception $e){
            //return new View("no hay campo existente", 500);
            $view = $this->view($e, 500);
            $view->setFormat('json');
            return $this->handleView($view);
        }
    }else{
        $view = $this->view('Unauthorized', 401);
        $view->setFormat('json');
        return $this->handleView($view);
    }
}

  /**
  * POST Route annotation.
  * @Post("/api/crearCategoria", name="post_categoria")
  */
  public function crearCategoria(Request $request)
  {
      try{
    $categoria = new Categoria();
    //$categoria->setId($request->get('id'));
    $categoria->setNom($request->get('nom'));

    $em = $this->getDoctrine()->getManager();
        $em->merge($categoria);
        $em->flush();

        $data = $categoria;

        // construim la resposta HTTP i l'enviem
        $view = $this->view($data, 200);
        $view->setFormat('json');
        return $this->handleView($view);
    }catch(\Exception $e){
        //return new View("no hay campo existente", 500);
        $view = $this->view($e, 500);
        $view->setFormat('json');
        return $this->handleView($view);
  }
  }
  

  /**
  * GET Route annotation.
  * @Get("/api/listCoche", name="get_coche")
  */
  public function getCoche()
  {
      $repository = $this->getDoctrine()->getRepository(Coche::class);
      // look for *all* Product objects
      $productes = $repository->findAll();

// el camp imatge que hem de subministrar al client es la URL que serveix la imatge
//      foreach ($productes as $producte) {
//        $producte->setImatge('/api/producteImatge/'.$producte->getId());
//      }

      $data = $productes;

// construim la resposta HTTP i l'enviem
      $view = $this->view($data, 200);
      $view->setFormat('json');
      return $this->handleView($view);
      return new View("no hay campo existente", Response::HTTP_NOT_FOUND);
  }

  /**
  * GET Route annotation.
  * @Get("/api/producteImatge/{id}")
  */
  public function getProducteImatge($id)
  {
      $repository = $this->getDoctrine()->getRepository(Coche::class);
      $producte = $repository->find($id);
      $file = $this->getParameter('imatges_directory') . '/' . $producte->getImatge();
      //http://symfony.com/doc/current/components/http_foundation.html#serving-files
      $response = new BinaryFileResponse($file);
      $response->headers->set('Content-Type', 'image/jpeg');

      return $response;
  }


/**
  * POST Route annotation.
  * @Post("/api/subirImagen", name="subir_imagen")
  */
  public function subirImagen(Request $request){
    //$final = json_decode($request->getContent(), true);
    //var_dump($request->files->get('imagenPropia'));
    //exit();

    $imagen = $request->files->get('imagenPropia');
    if (empty($imagen))
        {
            // construim la resposta HTTP i l'enviem
            $view = $this->view("No he recibido el fichero", 500);
            $view->setFormat('json');
            return $this->handleView($view);
        }else{

        $filename = $imagen->getClientOriginalName();//Aquí le da el nombre del fichero
        try {

            $imagen->move("../img", $filename);
        } catch (FileException $e){

            // construim la resposta HTTP i l'enviem
            $view = $this->view("Error al subir el fichero", 500);
            $view->setFormat('json');
            return $this->handleView($view);
        }

        // construim la resposta HTTP i l'enviem
        $view = $this->view("Fichero subido", 200);
        $view->setFormat('json');
        return $this->handleView($view);
        //return new Response("File uploaded",  Response::HTTP_OK,
        //    ['content-type' => 'text/plain']);
    }
}

/**
  * POST Route annotation.
  * @Post("/api/subirImagen1", name="subir_imagen1")
  */
  public function subirImagen1(Request $request){
    //$final = json_decode($request->getContent(), true);
    //var_dump($request->files->get('imagenPropia'));
    //exit();
    //mira el token y el usuario y si tiene permisos
    $response = $this->forward('App\Controller\UserController::fancyAction', [
        'token'  => $request->get('token')
    ]);
    //$userController = new UserController();
    //return $response;//$userController.credencialesToken($request->get('token')));
    if($response->getStatusCode() == 200){
        $imagen = $request->files->get('imagenPropia');
        if (empty($imagen))
            {
                // construim la resposta HTTP i l'enviem
                $view = $this->view("No he recibido el fichero", 500);
                $view->setFormat('json');
                return $this->handleView($view);
            }else{

            $filename = $imagen->getClientOriginalName();//Aquí le da el nombre del fichero
            try {

                $imagen->move("../img", $filename);
            } catch (FileException $e){

                // construim la resposta HTTP i l'enviem
                $view = $this->view("Error al subir el fichero", 500);
                $view->setFormat('json');
                return $this->handleView($view);
            }

            // construim la resposta HTTP i l'enviem
            $view = $this->view("Fichero subido", 200);
            $view->setFormat('json');
            return $this->handleView($view);
            //return new Response("File uploaded",  Response::HTTP_OK,
            //    ['content-type' => 'text/plain']);
        }
    }else{
        $view = $this->view('Unauthorized', 401);
        $view->setFormat('json');
        return $this->handleView($view);
    }
    //$final = json_decode($request->getContent(), true);
/*
    if(isset($final[$_FILES['imagenPropia']])){

		$imagen_tipo = $_FILES['imagenPropia']['type'];
		$imagen_nombre = $_FILES['imagenPropia']['name'];
		$directorio_final = "../micarpeta/".$imagen_nombre; 

		if($imagen_tipo == "image/jpeg" || $imagen_tipo == "image/jpg" || $imagen_tipo == "image/png"){

			if(move_uploaded_file($_FILES['imagenPropia']['tmp_name'], $directorio_final)){

				$data = array(
					'status' => 'success',
					'code' => 200,
					'msj' => 'Imagen subida'
				);
				$format = (object) $data;
				$json = json_encode($format); 
				echo $json; 

			}else{

				$data = array(
					'status' => 'error',
					'code' => 400,
					'msj' => 'Error al mover imagen al servidor'
				);
				$format = (object) $data;
				$json = json_encode($format); 
				echo $json; 

			}

		}else{

			$data = array(
				'status' => 'error',
				'code' => 500,
				'msj' => 'Formato no soportado'
			);
			$format = (object) $data;
			$json = json_encode($format); 
			echo $json; 

		}

	}else{

		$data = array(
			'status' => 'error',
			'code' => 400,
			'msj' => 'No se recibio ninguna imagen'
		);
		$format = (object) $data;
		$json = json_encode($format); 
		echo $json; 

	}*/
  }
  
}