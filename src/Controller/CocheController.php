<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CocheType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Coche;

class CocheController extends AbstractController
{
    /**
     * @Route("/coche", name="coche")
     */
    public function index(): Response
    {
        return $this->render('coche/index.html.twig', [
            'controller_name' => 'CocheController',
        ]);
    }
    /**
     * @Route("/coche/list", name="coche_list")
     */
    public function list()
    {
        $coches = $this->getDoctrine()
            ->getRepository(Coche::class)
            ->findAll();

        //codi de prova per visualitzar l'array de coches
         /*dump($coches);
         exit();*/

        return $this->render('coche/list.html.twig', ['coches' => $coches]);
    }
    /**
     * @Route("/coche/listJSON", name="coche_list_JSON")
     */
    public function listJSON()
    {
        $coches = $this->getDoctrine()
            ->getRepository(Coche::class)
            ->findAll();

        //codi de prova per visualitzar l'array de coches
         /*dump($coches);
         exit();*/
        
         echo("[{\"producteId\":\"3\",\"producteImatge\":\"assets\/img\/focus.jpeg\",\"producteNom\":\"Focus\",\"productePreu\":\"15000\",\"producteCategoria\":{\"categoriaId\":1,\"categoriaNom\":\"Ford\"}},
         {\"producteId\":\"5\",\"producteImatge\":\"assets\/img\/saxo.jpeg\",\"producteNom\":\"Saxo\",\"productePreu\":\"10000\",\"producteCategoria\":{\"categoriaId\":1,\"categoriaNom\":\"Renault\"}},
         {\"producteId\":\"6\",\"producteImatge\":\"assets\/img\/patrol.jpeg\",\"producteNom\":\"Patrol\",\"productePreu\":\"36000\",\"producteCategoria\":{\"categoriaId\":1,\"categoriaNom\":\"Nissan\"}}]");
         
        exit();
        
        //return $this->render('coche/listJSON.html.twig', ['coches' => $final]);
    }

}

