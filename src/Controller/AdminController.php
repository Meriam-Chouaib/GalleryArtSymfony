<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\User;
use App\Form\JobType;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\EditUSerType;
use App\Repository\JobRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
     * @Route("/admin")
     */
class AdminController extends AbstractController
{
   /**
    * @route("/", name="users")
    */
    public function userList(UserRepository $user)
    {
        return $this->render('admin/users.html.twig', [
            'users' => $user -> findAll()
        ]);
    }
     /**
     * @Route("/users/modifier/{id}", name="modifier_utilisateur")
     */

    public function edit(Request $request, User $user, EntityManagerInterface $em,$id ,UserRepository $userRepo){
        //request =yejbedlek données mte3 user 
        //User = win bch tsir modification du
        //Etitymanager : pour gestion bd
        //userRepository = récupération des données
          $form = $this -> createForm(EditUSerType::class, $user);
          $form->handleRequest($request);

           if($form->isSubmitted()&&$form->isValid()){
             
               $em->flush();//synchronisation d'enregistrement des données

               //si tout va bien donc redirect to listJob
            return $this->redirectToRoute("users");

    }
        return $this->render('admin/editUser.html.twig',["user" => $userRepo -> find($id),"formUser"=>$form->createView()]);
   }

      /**
     * @Route("/deleteUser/{id}", name="deleteUser")
     */

    public function delete($id){
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $user = $em-> getRepository(User::class)->find($id);


        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("users");
    }
   /**
     * @Route("/listProducts", Name="ListProducts")
     */
    public function Liste(JobRepository $repo){
        //on a injecté le JobRepository afin de recuperer données 
        $list= $repo->findAll();
        return $this->render('admin/products.html.twig',["jobs"=>$list]);

    }

    /**
     * @Route("/editProduct/{id}", name="editProduct")
     */

    public function editProduct(Request $request, $id){
        $job = new Job();
        $job = $this->getDoctrine()->getRepository(Job::class)->find($id);
        $form= $this->createForm(JobType::class, $job);
           $form->handleRequest($request);
           if($form->isSubmitted()&&$form->isValid()){
               $job= $form->getData();
           
               /*récuperer l'image 4lignes */
               $image=$form->get('image')->getData();
               //fonction de hachage yekhou ism fichier w yrodou chiffré bch meyabdech 3andhom nafs nom
               $fileName=md5(uniqid()).'.'.$image->getExtension();
               //bch n9olou win bch tsajalha bedhabt
               $image->move($this->getParameter('image_directory'),$fileName);
               $job->setImage($fileName);
               
               //l'ajout dans la base les données entityManager
               $entityManager= $this->getDoctrine()->getManager();
               $entityManager->persist($job); //enregistrement des données 
               $entityManager->flush();//synchronisation d'enregistrement des données

               //si tout va bien donc redirect to listJob
               return $this->redirectToRoute("ListJob");

    }
    return $this->render('admin/editProduct.html.twig',["form"=>$form->createView()]);
   }
     /**
     * @Route("/delete/{id}", name="deleteJobAdmin")
     */

    public function deleteProduct($id){
        $job = new Job();
        $em = $this->getDoctrine()->getManager();
        $job = $em-> getRepository(Job::class)->find($id);

        $em->remove($job);
        $em->flush();
        
        return $this->redirectToRoute("ListProducts");
    }

       /**
     * @Route("/listArtists", Name="ListArtists")
     */
    public function ListeCat(CategoryRepository $repo){
        //on a injecté le JobRepository afin de recuperer données 
        $list= $repo->findAll();
        return $this->render('admin/artists.html.twig',["categories"=>$list]);

    }
    /**
     * @Route("/editArtist/{id}", name="editArtist")
     */

    public function editArtist(Request $request, $id){
        $category = new Category();
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        $form= $this->createForm(CategoryType::class, $category);
           $form->handleRequest($request);
           if($form->isSubmitted()&&$form->isValid()){
               $category= $form->getData();
           
               /*récuperer l'image 4lignes */
               $image=$form->get('image')->getData();
               //fonction de hachage yekhou ism fichier w yrodou chiffré bch meyabdech 3andhom nafs nom
               $fileName=md5(uniqid()).'.'.$image->getExtension();
               //bch n9olou win bch tsajalha bedhabt
               $image->move($this->getParameter('image_directory'),$fileName);
               $category->setImage($fileName);
               
               
               //l'ajout dans la base les données entityManager
               $entityManager= $this->getDoctrine()->getManager();
               $entityManager->persist($category); //enregistrement des données 
               $entityManager->flush();//synchronisation d'enregistrement des données

               //si tout va bien donc redirect to listJob
               return $this->redirectToRoute("ListArtists");

    }
    return $this->render('admin/editArtist.html.twig',["form"=>$form->createView()]);
   }
    /**
     * @Route("/deleteArtist/{id}", name="deleteArtist")
     */

    public function deleteArtist($id){
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $category = $em-> getRepository(Category::class)->find($id);


        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("ListArtists");
    }
    

}
