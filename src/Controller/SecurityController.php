<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends Controller {

    public function __construct() {

    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('app_front_homepage_homepage');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout() {

    }

    /**
     * @Route("/registration", name="app_registration")
     */
    public function registrationAction(Request $request, UserService $userService) {
        $form = $this->createForm(UserType::class, new User());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userService->createUser($form->getData(), $form['password']->getData());
            return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
