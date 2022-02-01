<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    /**
     * @Route("/newCat", name="NewCategory")
     */
    
    public function New (Request $request){

        $category = new Category();
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

            //si tout va bien donc redirect to ListCategory
            return $this->redirectToRoute("ListCategory");
        }

        //awel mey7ot /new yraja3lou form fil page eli 3malneha b php bin/console create:form
        return $this->render('category/newCategory.html.twig',["form"=>$form->createView()]);

        
    }
     /**
     * @Route("/listCat", Name="ListCategory")
     */
    public function Liste(CategoryRepository $repo){
        //on a injecté le JobRepository afin de recuperer données 
        $list= $repo->findAll();
        return $this->render('category/listCategory.html.twig',["categories"=>$list]);

    }
     /**
     * @Route("/editCat/{id}", name="editCat")
     */

    public function edit(Request $request, $id){
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
               return $this->redirectToRoute("ListCategory");

    }
    return $this->render('category/edit.html.twig',["form"=>$form->createView()]);
   }
       /**
     * @Route("/deleteCat/{id}", name="deleteCat")
     */

    public function delete($id){
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $category = $em-> getRepository(Category::class)->find($id);


        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute("ListCategory");
    }
}




