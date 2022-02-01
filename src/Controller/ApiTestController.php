<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Repository\StageRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiTestController extends AbstractController
{


    /**
    * @Route("/list", name="ListStage", methods={"GET"})
    */
   public function Liste(StageRepository $repo){
    //conversion de tableau vers JSON et aprés return $response traja3 json content
     //récupération des données
     $list= $repo->findAll();
      // On spécifie qu'on utilise un encodeur en json
     $encoder = [new JsonEncoder()];
     //on instancie un normalisateur pour convertir la liste en tableau
     $norm = [new ObjectNormalizer()];
     //on instancie le convertisseur
     $serializer = new Serializer($norm, $encoder);
     //on convertit en json
     $jsonContent = $serializer->serialize($list, 'json',
     ['circular_reference_handler'=>function($object){
       return $object->getId();
     }]);
     //on instancie la réponse
     $response = new Response($jsonContent);
     // on ajoute l'entête http
     $response->headers->set('content-type', 'application/json');
     // on retourne la réponse
     return $response;
     }
     /**
     * @Route("/show/{id}", name="show_stage", methods={"GET"})
     */
public function Show(StageRepository $repo, $id){
    //récupération des données
    $stage= $repo->find($id);
    // On spécifie qu'on utilise un encodeur en json
    $encoder = [new JsonEncoder()];
    //on instancie un normalisateur pour convertir la liste en tableau
    $norm = [new ObjectNormalizer()];
    //on instancie le convertisseur
    $serializer = new Serializer($norm, $encoder);
    //on convertit en json
    $jsonContent = $serializer->serialize($stage, 'json',
    ['circular_reference_handler'=>function($object){
      return $object->getId();
    }]);
    //on instancie la réponse
    $response = new Response($jsonContent);
    // on ajoute l'entête http
    $response->headers->set('content-type', 'application/json');
    // on retourne la réponse
    return $response;
    }
  /**
  * @Route("/addStage", name="saveStage", methods={"POST"})
  */
  public function Add(Request $request){
    //on instancie un nouvel stage
    $stage= new Stage();
    //décodé les données Json-> données 3adi pouur l’insérer dans bd
      $donnees = json_decode($request->getContent(), true);
      //récupérer les données 
      $stage->setName($donnees['name']);
      $stage->setDescription($donnees['description']);
      //on sauvegarde dans la base de données
      $entityManager=$this->getDoctrine()->getManager();
      $entityManager->persist($stage);
      $entityManager->flush();
      return new Response('ok');
}

 /**
  * @Route("/editStage/{id}", name="saveStage", methods={"PUT"})
  */
  public function EditStage(Request $request,$id){
    //on instancie un nouvel stage
    $stage= $this->getDoctrine()->getRepository(Stage::class)->find($id);
    //décodé les données 
      $donnees = json_decode($request->getContent(), true);
      //récupérer les données 
      $stage->setName($donnees['name']);
      $stage->setDescription($donnees['description']);
      //on sauvegarde dans la base de données
      $entityManager=$this->getDoctrine()->getManager();
      $entityManager->flush();
      return new Response('ok');
  
}

   
}
