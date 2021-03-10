<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }
    /**
     * @Route("/afficher", name="question_indexx", methods={"GET"})
     */
    public function indexx(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/afficherstat/{idqs}", name="question_indexxx", methods={"GET","POST"})
     */
    public function indexxback(QuestionRepository $questionRepository,ReponseRepository $reponseRepository,$idqs): Response
    {
        $question=$questionRepository->find($idqs);
        $pieChart = new PieChart();
        $histogram = new Histogram();
        $col = new ColumnChart();
        $type='';
        if($question->getType()=='yes/no'){
            $yes=$reponseRepository->findbyreponseyes($question);
            $no=$reponseRepository->findbyreponseno($question);
            $nb1=0;
            $nb2=0;
            foreach ($yes as $row){
                $nb1++;
            }
            foreach ($no as $row){
                $nb2++;
            }

            $pieChart->getData()->setArrayToDataTable(
                [['Question', 'Reponse'],
                    ['Yes', $nb1],
                    ['NO',  $nb2],
                ]
            );
            $pieChart->getOptions()->setTitle('Reponse');
            $pieChart->getOptions()->setHeight(500);
            $pieChart->getOptions()->setWidth(900);
            $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
            $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
            $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
            $type="yes/no";
        }
        else {

            $r0=$reponseRepository-> findbyreponserate($question,0);
            $r1=$reponseRepository-> findbyreponserate($question,1);
            $r2=$reponseRepository-> findbyreponserate($question,2);
            $r3=$reponseRepository-> findbyreponserate($question,3);
            $r4=$reponseRepository-> findbyreponserate($question,4);
            $r5=$reponseRepository-> findbyreponserate($question,5);

            $nb10=0;
            $nb11=0;
            $nb12=0;
            $nb13=0;
            $nb14=0;
            $nb15=0;


            foreach ($r0 as $row){
                $nb10++;
            }
            foreach ($r1 as $row){
                $nb11++;
            }
            foreach ($r2 as $row){
                $nb12++;
            }foreach ($r3 as $row){
                $nb13++;
            }foreach ($r4 as $row){
                $nb14++;
            }foreach ($r5 as $row) {
                $nb15++;
            }


            $pieChart->getData()->setArrayToDataTable(
                [['Question', 'Reponse'],
                    ['0', $nb10],
                    ['1',  $nb11],
                    ['2',  $nb12],
                    ['3',  $nb13],
                    ['4',  $nb14],
                    ['5',  $nb15],

                ]
            );
            $pieChart->getOptions()->setTitle('Reponse');
            $pieChart->getOptions()->setHeight(500);
            $pieChart->getOptions()->setWidth(900);
            $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
            $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
            $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
            $type="rate";
        }
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
            'piechart' => $pieChart,
            'histogram' => $histogram,
            'col' => $col,
            'type'=> $type,
        ]);
    }

    /**
     * @Route("/new", name="question_new", methods={"GET","POST"})
     */
    public function new(Request $request,QuestionRepository $questionRepository): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $question->getImage();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $question->setImage($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $question->getImage();
            $filename = md5(uniqid()).'.'.$file->getExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $question->setImage($filename);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

  return $this->redirectToRoute('question_index');
    }
    /**
     * @Route( "/a/question", name="pdf" ,methods={"GET"})
     */
    public function generate_pdf( questionRepository $questionRepository): Response
    {

        $options = new Options();
        $options->set('defaultFont', 'Roboto');


        $dompdf = new Dompdf($options);
        $question = $questionRepository ->findAll();


        $html = $this->renderView('question/pdf.html.twig', [
            'questions' => $question,
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("testpdf.pdf", [
            "Attachment" => false
        ]);
    }
}
