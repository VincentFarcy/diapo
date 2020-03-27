<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(AlbumRepository $albumRepository)
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'albums' => $albumRepository->findAll(),
        ]);
    }
}
