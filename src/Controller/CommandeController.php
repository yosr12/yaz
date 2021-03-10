<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Produit;

use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="commande_index", methods={"GET"})
     */
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new{idproduit}", name="commande_new", methods={"GET","POST"})
     */
    public function new(Request $request,$idproduit,\Swift_Mailer $mailer): Response
    {
        $commande = new Commande();
        $em=$this->getDoctrine()->getManager();
        $produit=$em->getRepository(produit::class)->find($idproduit);
        $commande->addProduit($produit);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $commande->setEtat("En cours") ;
            $prix = $produit->getPrix() * $commande->getNbproduit() ;
            $commande->setPrixtotale($prix) ;
            $produit->setQuantite( ($produit->getQuantite())- $commande->getNbproduit() ) ;
            if ($produit->getQuantite() < 10){
                $message = (new \Swift_Message('Produit'))
                    ->setFrom('aminos.ayari@gmail.com')
                    ->setTo('mohamedamine.masseoudi@esprit.tn')
                    ->setBody($produit->getNom() ,
                        "produit epuise")
                ;
                $mailer->send($message);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commande_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commande $commande): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commande_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commande $commande): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_index');
    }
    /**
     * @Route("/stat/commande",name="statistiquesss")
     */
    public function statistiques(): Response
    {
        $p=$this->getDoctrine()->getRepository(Commande::class);
        $nbs = $p->getNb();
        $data = [['Produit', 'Nombre de commandes']];
        foreach($nbs as $nb)
        {
            $data[] = array(
                $nb['prod'], $nb['com'])
            ;
        }
        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable(
            $data
        );
        $bar->getOptions()->setTitle('Nombre de commmandes par produit');
        $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
        $bar->getOptions()->getTitleTextStyle()->setFontSize(25);
        return $this->render('commande/Stat.html.twig',
            array('piechart' => $bar,'nbs' => $nbs));

    }
    /**
     * @Route( "/a/commande", name="pdf" ,methods={"GET"})
     */
    public function generate_pdf(CommandeRepository $commandeRepository): Response
    {

        $options = new Options();
        $options->set('defaultFont', 'Roboto');
        $dompdf = new Dompdf($options);
        $commande = $commandeRepository->findAll();
        $html = $this->renderView('commande/pdf.html.twig', [
            'commandes' => $commande,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("testpdf.pdf", [
            "Attachment" => false
        ]);
    }

}
