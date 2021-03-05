<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function inscrit(Request $request)
    {
        $User = new User();
        $form=$this->createForm(UserType::Class,$User);
        $form->add('Register', SubmitType::class);

        $form->handleRequest($request);

       if ($form->isSubmitted()&& $form->isValid()){
        $User = $form->getData();
        $em=$this->getDoctrine()->getManager();

        $em->persist($User);
        $em->flush();
        return $this->redirectToRoute('users');
}
        return $this->render('users/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
 
 /**
     * @Route("/listUser", name="listUser")
     */
    public function listUser()

   {
    $repository=$this->getDoctrine()->getRepository(User::Class);
    $Users=$repository->findAll();

    return $this->render('users/listusers.html.twig', [
        
        'Users' => $Users,
    ]);
    
    }
    
    /**
     * @Route("/updateUser/{id}", name="updateUser")
     */
    public function updateUser(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $User = $em->getRepository(User::class)->find($id);
        $form = $this->createForm(UserType::class, $User);
        
        $form->add('Modifier',SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('listUser');
        }

        return $this->render('users/updateUser.html.twig', [
                        'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/deleteUser/{id}", name="deleteUser")
     */
    public function deleteUser($id)
    {
      
        $em=$this->getDoctrine()->getManager();
        $User = $em->getRepository(User::class)->find($id);
        $em->Remove($User);
         $em->flush();

           return $this->redirectToRoute('listUser');

    }

 /**
     * @Route("/showUser/{id}", name="showUser")
     */

    public function showUser($id): Response
    {
        $repository=$this->getDoctrine()->getRepository(User::Class);
        $User=$repository->find($id);

        return $this->render('users/showUser.html.twig', [
            'User' => $User,
        ]);
}

  /**
     * @Route("/login", name="login")
     */
    public function login(){

     
        return $this->render('users/login.html.twig' );
    
}
   

     /**
     * @Route("/logout", name="test_logout")
     */
    public function logout(){}
}
