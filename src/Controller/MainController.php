<?php

namespace App\Controller;

use App\Entity\Album;
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
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/album/{id}", name="album_read", requirements={"id": "\d+"})
     */
    public function read(Album $album)
    {
        return $this->render('main/read.html.twig', [
            'album' => $album,
        ]);
    }
}
