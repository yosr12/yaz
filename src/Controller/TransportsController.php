<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Transport;
use App\Form\TransportType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class TransportsController extends AbstractController

{
    /**
     * @Route("/transports", name="transports")
     */
    public function transports(): Response
    {
        $repository=$this->getDoctrine()->getRepository(Transport::Class);
        $Transports=$repository->findAll();
        return $this->render('transports/transports.html.twig', [
            'transports' => $Transports,
        ]);
    }
    /**
     * @Route("/admin/transports", name="admintransports")
     */
    public function admintransports(): Response
    {
        $repository=$this->getDoctrine()->getRepository(Transport::Class);
        $Transports=$repository->findAll();
        return $this->render('transports/admintransport.html.twig', [
            'transports' => $Transports,
        ]);
    }
    /**
     * @Route("/transport/add", name="newTransport")
     */

    public function newTransport(Request $request)
    {
        $Transport=new Transport();
        $form=$this->createForm(TransportType::class,$Transport);
        
        $form->add('Envoyer', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $Transport=$form->getData();
            $em=$this->getDoctrine()->getManager();
            $em->persist($Transport);
            $em->flush();
            return $this->redirectToRoute('admintransports');
        }
        return $this->render('transports/add.html.twig',
         ['form' => $form->createView()]);
  
    }
    /**
     * @Route("/updateTransport/{id}", name="updateTransport")
     */
    public function updateTransport(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $Transport=$em->getRepository(Transport::class)->find($id);
        $form=$this->createForm(TransportType::class,$Transport);
        
        $form->add('Envoyer', SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $em->flush();
            return $this->redirectToRoute('admintransports');

        }
        
        return $this->render('transports/add.html.twig',
         ['form' => $form->createView()]);
  
    }
    /**
     * @Route("/deleteTransport/{id}", name="deleteTransport")
     */
    public function deleteTransport(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $Transport=$em->getRepository(Transport::class)->find($id);
        $em->remove($Transport,$id);
        $em->flush();
        
        return $this->redirectToRoute('admintransports');

      
    }
    /**
     * @Route("/transportdetails/{id}", name="transportdetails")
     */
    public function transportdetails(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $Transport=$em->getRepository(Transport::class)->find($id);
        
        
        return $this->render('transports/transportdetails.html.twig',[
            'transport' => $Transport,
         ]);
  
    }

       
}
