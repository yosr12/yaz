<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
   
     /**
     * @Route("/reclamation", name="reclamation")
     */
    public function reclamation(Request $request)
    {
        $Reclamation = new Reclamation();
        $form=$this->createForm(ReclamationType::Class,$Reclamation);
        $form->add('Send', SubmitType::class);

        $form->handleRequest($request);

       if ($form->isSubmitted()){
        $Reclamation = $form->getData();
        $em=$this->getDoctrine()->getManager();

        $em->persist($Reclamation);
        $em->flush();
        return $this->redirectToRoute('listRec');
}
           return $this->render('reclamation/reclamation.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/listRec", name="listRec")
     */
    public function listReclamation()

   {
    $repository=$this->getDoctrine()->getRepository(Reclamation::Class);
    $Reclamations=$repository->findAll();

    return $this->render('reclamation/listRec.html.twig', [
        
        'Reclamations' => $Reclamations,
    ]);
    
    }

    /**
     * @Route("/updatereclamation/{id}", name="updatereclamation")
     */
    public function updatereclamation(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReclamationType::class, $Reclamation);
        
        $form->add('Modifier',SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('listRec');
        }

        return $this->render('reclamation/updateReclamation.html.twig', [
                        'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deletereclamation/{id}", name="deletereclamation")
     */
    public function deleteReclamation($id)
    {
      
        $em=$this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->Remove($Reclamation);
         $em->flush();

           return $this->redirectToRoute('listRec');

    }

    /**
     * @Route("/showreclamation/{id}", name="showreclamation")
     */

    public function showreclamation($id): Response
    {
        $repository=$this->getDoctrine()->getRepository(Reclamation::Class);
        $Reclamation=$repository->find($id);

        return $this->render('reclamation/showReclamation.html.twig', [
            'Reclamation' => $Reclamation,
        ]);
}
 /**
     * @Route("/listrecBack", name="listrecBack")
     */
    public function listrecBack()

   {
    $repository=$this->getDoctrine()->getRepository(Reclamation::Class);
    $Reclamations=$repository->findAll();

    return $this->render('reclamation/listrecBack.html.twig', [
        
        'Reclamations' => $Reclamations,
    ]);
    
    }
 /**
     * @Route("/showreclamationBack/{id}", name="showreclamationBack")
     */

    public function showreclamationBack($id): Response
    {
        $repository=$this->getDoctrine()->getRepository(Reclamation::Class);
        $Reclamation=$repository->find($id);

        return $this->render('reclamation/showreclamationBack.html.twig', [
            'Reclamation' => $Reclamation,
        ]);
}

    /**
     * @Route("/deletereclamationBack/{id}", name="deletereclamationBack")
     */
    public function deletereclamationBack($id)
    {
      
        $em=$this->getDoctrine()->getManager();
        $Reclamation = $em->getRepository(Reclamation::class)->find($id);
        $em->Remove($Reclamation);
         $em->flush();

           return $this->redirectToRoute('listrecBack');

    }
 
}
