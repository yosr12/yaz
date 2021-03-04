<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use App\Entity\Sujet;
use App\Form\CommentaireType;
use App\Form\SujetType;
use App\Repository\SujetRepository;
use App\Repository\CommentaireRepository;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/sujet")
 */
class SujetController extends AbstractController
{
    /**
     * @Route("/test", name="sujet_index", methods={"GET"})
     */
    public function index(SujetRepository $sujetRepository): Response
    {
        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujetRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sujet_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sujet = new Sujet();
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $sujet->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $sujet->setImage($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sujet);
            $entityManager->flush();

            return $this->redirectToRoute('sujet_index');
        }

        return $this->render('sujet/new.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/{iduser}", name="sujet_show", methods={"GET","POST"}, requirements={"id":"\d+","iduser":"\d+"})
     * @ParamConverter("id", options={"id"= "id"})
     * @ParamConverter("iduser", options={"iduser"= "iduser"})
     */
    public function show(Sujet $sujet, Request $request, $id,$iduser): Response
    {


        $commentaire = new Commentaire();


        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();
        $utilisateur = $em->getRepository( Utilisateur::class)->find($iduser) ;
        $sujet=$em->getRepository( Sujet::class)->find($id) ;

        $commentaire->setSujet($sujet);
        $commentaire->setUtilisateur($utilisateur);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('sujet_show', array('id' => $id,'iduser'=>1));
        }

        return $this->render('sujet/show.html.twig', [
            'sujet' => $sujet,
            'commentaire' => $commentaire,
            'form' => $form->createView(),


        ]);
    }

    /**
     * @Route("/{id}/edit/test", name="sujet_edit", methods={"GET","POST"}, )
     * @ParamConverter("id", options={"id"= "id"})
     */
    public function edit(Request $request, Sujet $sujet): Response
    {
        $sujet->setImage(null);
        $form = $this->createForm(SujetType::class, $sujet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $sujet->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $sujet->setImage($filename);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sujet_index');
        }

        return $this->render('sujet/edit.html.twig', [
            'sujet' => $sujet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="sujet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sujet $sujet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sujet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sujet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sujet_index');
    }


}
