<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Author;
use App\Entity\Tag;
use App\Repository\AlbumRepository;
use App\Repository\AuthorRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @Route("/albums", name="albums")
     */
    public function browse(
        AlbumRepository $albumRepository, 
        TagRepository $tagRepository,
        AuthorRepository $authorRepository)
    {
        return $this->render('main/index.html.twig', [
            'albums' => $albumRepository->findAll(),
            'tags' => $tagRepository->findAllWithAlbums(),
            'authors' => $authorRepository->findAllWithAlbums(),
        ]);
    }

    /**
     * @Route("/albums/byTag/{id}", name="albums_by_tag", requirements={"id": "\d+"})
     */
    public function browseByTag(
        $id,
        AlbumRepository $albumRepository, 
        TagRepository $tagRepository,
        AuthorRepository $authorRepository,Request $request)
    {
        if ($id == 0) {
            return $this->redirectToRoute('home');
        } else {
            $tag = $tagRepository->find($id);
        }

        return $this->render('main/index.html.twig', [
            'albums' => $albumRepository->findByTag($tag->getId()),
            'tags' => $tagRepository->findAllWithAlbums(),
            'authors' => $authorRepository->findAllWithAlbums(),
        ]);
    }

    /**
     * @Route("/albums/byAuthor/{id}", name="albums_by_author", requirements={"id": "\d+"})
     */
    public function browseByAuthor(
        $id,
        AlbumRepository $albumRepository, 
        TagRepository $tagRepository,
        AuthorRepository $authorRepository)
    {
        if ($id == 0) {
            return $this->redirectToRoute('home');
        } else {
            $author = $authorRepository->find($id);
        }

        return $this->render('main/index.html.twig', [
            'albums' => $albumRepository->findByAuthor($author->getId()),
            'tags' => $tagRepository->findAllWithAlbums(),
            'authors' => $authorRepository->findAllWithAlbums(),
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
