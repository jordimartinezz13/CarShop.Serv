<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CategoriaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Categoria;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categoria", name="categoria")
     */
    public function index(): Response
    {
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
        ]);
    }
    /**
     * @Route("/categoria/list", name="categoria_list")
     */
    public function list()
    {
        $categorias = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

        //codi de prova per visualitzar l'array de categoria
         /*dump($categorias);
         exit();*/

        return $this->render('categoria/list.html.twig', ['categorias' => $categorias]);
    }

    /**
     * @Route("/categoria/listJSON", name="categoria_list_JSON")
     */
    public function listJSON()
    {
        $categoria = $this->getDoctrine()
            ->getRepository(Categoria::class)
            ->findAll();

        //codi de prova per visualitzar l'array de coches
         /*dump($coches);
         exit();*/
         
        
         echo("[{\"categoriaId\":1,\"categoriaNom\":\"Ford\"},{\"categoriaId\":1,\"categoriaNom\":\"Renault\"},
         {\"categoriaId\":1,\"categoriaNom\":\"Nissan\"}]");
         
        exit();
        
        //return $this->render('coche/listJSON.html.twig', ['coches' => $final]);
    }

    /**
     * @Route("/categoria/new", name="categoria_new")
     */
    public function new(Request $request)
    {
        $categoria = new Categoria();

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe TascaType 
        //$form = $this->createForm(CategoriaType::class, $categoria, array('submit'=>'Crear Categoria'));

        //$form->handleRequest($request);

        if ($request=="POST") {

            //$input = $_POST;

            //$tasca = $form->getData();
            $datos = json_decode($request->getContent(), true);

            $categoria = new Categoria(0, $data['nom']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoria);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nova tasca '.$categoria->getNom().' creada!'
            );

            return true;
        }
        return false;
    }

}




























//    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
//    $input = $_POST;
//    $sql = "INSERT INTO posts
//          (title, status, content, user_id)
//          VALUES
//          (:title, :status, :content, :user_id)";
//    $statement = $dbConn->prepare($sql);
//    bindAllValues($statement, $input);
//    $statement->execute();
//    $postId = $dbConn->lastInsertId();
//    if($postId)
//    {
//      $input['id'] = $postId;
//      header("HTTP/1.1 200 OK");
//      echo json_encode($input);
//      exit();
//   }
//}

