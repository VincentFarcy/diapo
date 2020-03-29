<?php

namespace App\Controller\Admin;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/album", name="admin_album_")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("s", name="browse")
     */
    public function browse(AlbumRepository $albumRepository)
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);

        return $this->render('admin/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //dd($album);

            $em->persist($album);
            $em->flush();

            $this->addFlash('success', 'Album "' . $album->getTitle() . '" ajouté');
        }
        
        return $this->redirectToRoute('admin_album_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(Album $album, AlbumRepository $albumRepository, Request $request, EntityManagerInterface $em)
    {
        $form_edit = $this->createForm(AlbumType::class, $album);

        $newAlbum = new Album();
        $form_add = $this->createForm(AlbumType::class, $newAlbum);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $album->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Album "' . $album->getTitle() . '" modifié');

            return $this->redirectToRoute('admin_album_browse');
        }
        
        return $this->render('admin/album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
            'album_edit' => $album,
            'form_add' => $form_add->createView(),
            'form_edit' => $form_edit->createView(),
        ]);   
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}), methods={"DELETE"})
     */
    public function delete(Album $album, Request $request, EntityManagerInterface $em)
    {
        if($request->headers->get('referer') !== null) {

            $em->remove($album);
            $em->flush();

            $this->addFlash('success', 'Album "' . $album->getTitle() . '" supprimé');
        }
        
        return $this->redirectToRoute('admin_album_browse');
    }
}
