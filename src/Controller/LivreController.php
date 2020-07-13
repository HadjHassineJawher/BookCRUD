<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use OC\PlatformBundle\Form\AdvertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class LivreController extends AbstractController
{
    /**
     * @Route("/livre", name="livre")
     */
    public function index(LivreRepository $rep)
    {
        // $rep==$this->getDoctrine();->getRepository(Livre::class);
        // $doctrine=$this->getDoctrine();
        // $rep=$doctrine->getRepository(Livre::class);
        $livre=$rep->findAll();
        return $this->render('livre/index.html.twig',['livre'=>$livre]);
    }
    /**
    * @Route("/livre/find/{id}", name="livre_find")
    */
    public function rechercher(Livre $livre/*$id,LivreRepository $rep*/){
        //$doctrine=$this->getDoctrine();
        //$rep=$doctrine->getRepository(Livre::class);
        //$livre=$rep->find($id);
        //return $this->render('livre/rechercher.html.twig',['livre'=>$livre]);
        return $this->render('livre/rechercher.html.twig',['livre'=>$livre]);
    }
    /**
    * @Route("/livre/add", name="livre_add")
    */
    public function ajouter(){
        $livre = new Livre();
        $livre->setTitre('Titre du livre');
        $date="08-09-2018";
        $livre->setDateEdition(new \DateTime($date));
        $livre->setEditeur('Editeur du livre');
        $livre->setImage('https://ipsumimage.appspot.com/640x360');
        $livre->setResume('livrelivrelivrelivrelivrelivrelivrelivrelivrelivrelivrelivrelivre');
        $doctrine=$this->getDoctrine();
        $em=$doctrine->getManager();
        $em->persist($livre);
        $em->flush();
    return $this->redirectToRoute('livre');
    }

    /**
     * @Route("livre/ajouter1", name="Methode1")
     */
    public function ajouter1(Request $request, EntityManagerInterface $manager)
    {
        $livre = new Livre();
        if ($request->isMethod("post")){
            $livre->setTitre($request->get('titre'));
            $livre->setDateEdition(new \DateTime($request->get('date')));
            $livre->setEditeur($request->get('editeur'));
            $livre->setImage($request->get('image'));
            $livre->setResume($request->get('resume'));

            $manager->persist($livre);
            $manager->flush();
            return $this->redirectToRoute('livre');
           //return $this->redirectToRoute('livre_find',['id'=>$livre->getId()]);
        }
        return $this->render("livre/ajouter1.html.twig");
    }

    /**
     * @Route("livre/ajouter2", name="Methode2")
     */
    public function ajouter2(Request $request, EntityManagerInterface $manager)
    {
        $livre = new Livre();
        $form=$this->createFormBuilder($livre)
                    ->add('Titre',TextType::class,['attr'=>['placeholder'=>"Saisir le Titre du Livre",'class'=>'form-group']])
                    ->add('dateEdition',dateType::class,['attr'=>['class'=>'form-group']])
                    ->add('editeur',TextType::class,['attr'=>['placeholder'=>"Saisir le nom de l'Editeur",'class'=>'form-group']])
                    ->add('image',TextType::class,['attr'=>['placeholder'=>"Saisir l'Url de Image",'class'=>'form-group']])
                    ->add('resume',TextareaType::class,['attr'=>['placeholder'=>"Saisir le Résumé du livre",'class'=>'form-group']])
                    ->add('Ajouter',SubmitType::class,['attr'=>['class'=>'btn btn-block btn-success']])
                    ->getForm();

        if ($request->isMethod("post")){
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $manager->persist($livre);
                $manager->flush();
            //return $this->redirectToRoute('livre_find',['id'=>$livre->getId()]);
            return $this->redirectToRoute('livre');
            }
        }
        return $this->render("livre/ajouter2.html.twig",['formLivre'=>$form->createView()]);
    }

    /**
     * @Route("livre/supprimer/{id}", name="Suppression")
     */
    public function supprimer($id){
        $doctrine=$this->getDoctrine();
        $livre=$doctrine->getRepository(Livre::class)->find($id);
        $em=$doctrine->getManager();
        $em->remove($livre);
        $em->flush();
        return $this->redirectToRoute('livre');
    }

    /**
    * @Route("/livre/ajouter3", name="livre_add3")
    * @Route("/livre/editer/{id}", name="livre_update")
    */
    public function ajouter3(Livre $livre=null,Request $request,EntityManagerInterface $manager){
        if(!$livre){
             $livre = new Livre();
        }

        $form=$this->createForm(LivreType::class,$livre);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $manager->persist($livre);
            $manager->flush();
            return $this->redirectToRoute('livre_find',['id'=>$livre->getId()]);
        }

    return $this->render("livre/ajouter3.html.twig",['formLivre'=>$form->createView()]);
    }
 
}
