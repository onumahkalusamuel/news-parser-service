<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/admin', name: 'login-as-admin')]
    public function admin(Session $session): Response
    {
        $session->set('role', 'admin');
        return $this->redirectToRoute('app_news_index');
    }

    #[Route('/moderator', name: 'login-as-moderator')]
    public function moderator(Session $session): Response
    {
        $session->set('role', 'moderator');
        return $this->redirectToRoute('app_news_index');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Session $session): Response
    {
        $session->set('role', null);
        return $this->redirectToRoute('app_news_index');
    }
}
