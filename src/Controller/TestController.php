<?php

namespace App\Controller;


use App\Entity\Job;
use App\Form\JobType;
use App\Repository\JobRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController {
    
    
    /**
     * @Route("/save", name="SaveJob")
     */
    public function Save(){
        $entityManager=$this->getDoctrine()->getEntityManager();
        
        $j= new Job();
        $j->setName('dv');
        $j->setDescription('dv_description');

        $entityManager->persist($j);//toujours en enregistre les instanciations
        $entityManager->flush();
        return new Response("job ajouté ".$j->getId() );

    }
    /**
     * @Route("/listJob", Name="ListJob")
     */
    public function Liste(JobRepository $repo){
        //on a injecté le JobRepository afin de recuperer données 
        $list= $repo->findAll();
        return $this->render('job/list.html.twig',["jobs"=>$list]);

    }
      /**
     * @Route("/home", Name="Home")
     */
    public function home(JobRepository $repo){
        //on a injecté le JobRepository afin de recuperer données 
        $list= $repo->findAll();
       
        return $this->render('pages/home.html.twig',["jobs"=>$list]);

    }
  
    
    /**
     * @Route("/job/{id}", name="JobById")
     */
    public function JobById($id){
        $j = $this->getDoctrine()->getRepository(job::class)->find($id);
    return $this->render('job/detail.html.twig',['job'=>$j]);
    }

    /**
     * @IsGranted("ROLE_EDITOR")
     * @Route("/newj", name="newJob")
     */

    public function New (Request $request){
        $job = new Job();
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

        //awel mey7ot /new yraja3lou form fil page eli 3malneha b php bin/console create:form
        return $this->render('job/new.html.twig',["form"=>$form->createView()]);

        
    }
    
    /**
     * @Route("/editJob/{id}", name="editJob")
     */

     public function edit(Request $request, $id){
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
     return $this->render('job/editJob.html.twig',["form"=>$form->createView()]);
    }

     /**
     * @Route("/delete/{id}", name="deleteJob")
     */

    public function delete($id){
        $job = new Job();
        $em = $this->getDoctrine()->getManager();
        $job = $em-> getRepository(Job::class)->find($id);

        $em->remove($job);
        $em->flush();
        
        return $this->redirectToRoute("ListJob");
    }

      /**
     * @Route("/contactUs", name="contactUs")
     */
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }
       /**
     * @Route("/signIn", name="signIn")
     */
    public function signin(): Response
    {
        return $this->render('pages/signin.html.twig');
    }
    /**
     * @Route("/signUp", name="signUp")
     */
    public function signUp(): Response
    {
        return $this->render('pages/signup.html.twig');
    }

}