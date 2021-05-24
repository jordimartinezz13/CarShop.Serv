<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
//require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;

class UserController extends AbstractFOSRestController
{
    /*#[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }*/

  /**
  * POST Route annotation.
  * @Post("/firebase/credencials", name="credencials")
  */
  public function miraCredenciales(Request $request)
  {
    //var_dump( $request->get("username"));
    //exit();
    //$users = $em->getRepository('MyProject\Domain\User')->findBy(array('age' => 20, 'surname' => 'Miller'));
    $user = new User();
    //$categoria->setId($request->get('id'));
    $user->setUsername($request->get('username'));
    $user->setPassword($request->get('password'));
    $user->setPermisos("a");

    //var_dump( $request->get("username"));
    //exit();

    $em = $this->getDoctrine()->getRepository(User::class)
        ->findBy(array('username' => $user->getUsername(), 'password' => $user->getPassword()),array(), 1);//array es porque puedo poner order by y lo de 1 es el limit de la respuesta

        if (!$em) {
            $view = $this->view('Unauthorized', 401);
            $view->setFormat('json');
            return $this->handleView($view);
        }

        $data = $em;
        //var_dump($em[0]->getId());
        //exit();
        // Ver el ejemplo de password_hash() para ver de dónde viene este hash.
        //$hash = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';

        //if (password_verify('rasmuslerdorf', $hash)) {
        //    echo '¡La contraseña es válida!';
        //} else {
        //    echo 'La contraseña no es válida.';
        //}
        
        // construim la resposta HTTP i l'enviem

        $time = time();
        //$key = 'my_secret_key';
        $key = $user->getPassword();

        $token = array(
            'sub' => $user->getUsername() . $user->getPassword(), //sujeto, concatenado nombre usuario y password
            'iat' => $time, // Tiempo que inició el token
            'exp' => $time + (60*60), // Tiempo que expirará el token (+1 hora)
            'jti' => 'idusr-'.$user->getUsername(),//JWT identificador del token
            'data' => [ // información del usuario
                'id' => 1,
                'name' => $user->getUsername()
            ]
        );

        $jwt = JWT::encode($token, $key);
        //carga el token en la BBDD
        if( $this->upDateToken($jwt, $em[0]->getId()) == "error"){
            throw $this->createNotFoundException(
                'ERROR AL CREAR EL TOKEN '
            );
        }


        //$data = JWT::decode($jwt, $key, array('HS256'));

        //var_dump($data);
        $view = $this->view($jwt, 200);
        $view->setFormat('json');

            
        return $this->handleView($view);
  }

  function upDateToken($token, $id){
    $entityManager = $this->getDoctrine()->getManager();
    $user = $entityManager->getRepository(User::class)->find($id);
    //$entityManager = $this->getDoctrine()->getRepository(User::class)->find($id);

    if (!$entityManager) {
        return "error";
    }

    $user->setApiToken($token);
    $entityManager->flush();
    return "actualizado";
    
  }

  /**
  * GET Route annotation.
  * @Get("/firebase/credencialsTOKEN", name="credencials_token")
  */
  public function miraCredencialesToken(Request $request)
  {
      //var_dump(get_headers($request, "Authorization"));
      //var_dump($request->headers->get('Authorization', ''));
      //var_dump($request->get('username'));
      //exit();
    $entityManager = $this->getDoctrine()->getManager();
    $users = $entityManager->getRepository(User::class)
    //->findBy(array('api_token' => $request->get('token')),array(), 1);
    //->findOneBy(['api_token' => $request->get('token')]);
    ->findAll();
    $usuario=null;
    foreach ($users as $clave => $valor) {
        if( $valor->getApiToken() == $request->get('token')){
            $usuario = $valor;
        }
    }
    
    if($usuario==null){
        $entityManager=null;
    }
    //var_dump($usuario);
    //exit;

    
    if (!$entityManager) {
        $view = $this->view('Unauthorized', 401);
        $view->setFormat('json');
        return $this->handleView($view);
    }else{
        //$data = JWT::decode($token, $user->getPassword(), array('HS256'));
        //$data=$user;
        $data = JWT::decode($request->get('token'), $usuario->getPassword(), array('HS256'));
        if($data){
            $view = $this->view($usuario->getUsername(), 200);
            $view->setFormat('json');
            return $this->handleView($view);
        }else{
            $view = $this->view('Unauthorized', 401);
            $view->setFormat('json');
            return $this->handleView($view);
        }
    }
  }

  /**
  * POST Route annotation.
  * @Post("/firebase/credencialsTOKENp", name="credencials_token_p")
  */
  public function miraCredencialesTokenPost(Request $request)
  {
      //var_dump(get_headers($request, "Authorization"));
      //var_dump($request->headers->get('Authorization', ''));
      //var_dump($request->get('username'));
      //exit();
    $entityManager = $this->getDoctrine()->getManager();
    $users = $entityManager->getRepository(User::class)
    //->findBy(array('api_token' => $request->get('token')),array(), 1);
    //->findOneBy(['api_token' => $request->get('token')]);
    ->findAll();
    $usuario=null;
    foreach ($users as $clave => $valor) {
        if( $valor->getApiToken() == $request->get('token')){
            $usuario = $valor;
        }
    }
    
    if($usuario==null){
        $entityManager=null;
    }
    //var_dump($usuario);
    //exit;

    
    if (!$entityManager) {
        $view = $this->view('Unauthorized', 401);
        $view->setFormat('json');
        return $this->handleView($view);
    }else{
        //$data = JWT::decode($token, $user->getPassword(), array('HS256'));
        //$data=$user;
        $data = JWT::decode($request->get('token'), $usuario->getPassword(), array('HS256'));
        if($data){
            $view = $this->view($usuario->getUsername(), 200);
            $view->setFormat('json');
            return $this->handleView($view);
        }else{
            $view = $this->view('Unauthorized', 401);
            $view->setFormat('json');
            return $this->handleView($view);
        }
    }
  }


  public function fancyAction($token)
  {
    $entityManager = $this->getDoctrine()->getManager();
    $users = $entityManager->getRepository(User::class)->findAll();
    $usuario=null;
    foreach ($users as $clave => $valor) {
        if( $valor->getApiToken() == $token){
            $usuario = $valor;
        }
    }
    if($usuario==null){
        $entityManager=null;
    }
    if (!$entityManager) {
        $view = $this->view('Unauthorized', 401);
        $view->setFormat('json');
        return $this->handleView($view);
        //return false;
    }else{
        $data = JWT::decode($token, $usuario->getPassword(), array('HS256'));
        if($data){
            $view = $this->view($usuario->getUsername(), 200);
            $view->setFormat('json');
            return $this->handleView($view);
            //return true;
        }else{
            $view = $this->view('Unauthorized', 401);
            $view->setFormat('json');
            return $this->handleView($view);
            //return false;
        }
    }
  }
}
