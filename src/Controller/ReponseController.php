<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\Reservation;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reponse_new", methods={"GET","POST"})
     */
    public function new(Request $request,QuestionRepository $questionRepository): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);



        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'formObject' => $form,
            'questions' => $questionRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new/{answer}/{idquestion}", name="reponse_ajouter", methods={"GET","POST"})
     */
    public function ajouterreponse(Request $request,QuestionRepository $questionRepository,$answer,$idquestion): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager() ;
        $question = $em->getRepository(Question::class)->find($idquestion) ;
        $reponse->setQuestion($question);

        if($answer==10){
            $reponse->setReponse("yes");
        }
        elseif($answer==20){
            $reponse->setReponse("no");
        }
        else{
            $reponse->setReponse(strval($answer));
        }


        $em->persist($reponse);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'formObject' => $form,
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reponse $reponse): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reponse_index');
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reponse $reponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_index');
    }
    /**
     * @Route("/afficherbackR", name="reponse_show", methods={"GET"})
     */
    public function ShowRR(): Response
    {
        $reponse = $this->getDoctrine()->getRepository(Reponse::class)->findAll() ;
        return $this->render('reponse/showback.html.twig', [
            'reponses' => $reponse,
        ]);
    }
}
