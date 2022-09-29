<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/', name: 'app_news_index')]
    public function index(Request $request, NewsRepository $newsRepository, Session $session): Response
    {
        if (!in_array($session->get('role'), ['admin', 'moderator']))
            return $this->redirectToRoute('login');

        $offset = max(0, $request->query->getInt('offset', 0));
        $perpage = 10;

        $news = $newsRepository->getNewsPaginator($perpage, $offset);

        return $this->render('news/index.html.twig', [
            'news' => $news,
            'previous' => $offset - $perpage,
            'next' => min(count($news), $offset + $perpage),
        ]);
    }

    #[Route('/{id}', name: 'app_news_delete', methods: ['POST'])]
    public function delete(Request $request, News $news, NewsRepository $newsRepository, Session $session): Response
    {
        if ($session->get('role') !== 'admin')
            return $this->redirectToRoute('login');

        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $newsRepository->remove($news, true);
        }

        return $this->redirectToRoute('app_news_index', [], Response::HTTP_SEE_OTHER);
    }
}
