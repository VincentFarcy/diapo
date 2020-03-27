<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/image", name="admin_image_")
 */
class ImageController extends AbstractController
{
    /**
     * @Route("s", name="browse")
     */
    public function browse(ImageRepository $imageRepository)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAll(),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->getData();

            $em->persist($image);
            $em->flush();

            $this->addFlash('success', 'Auteur ' . $image->getSrc() . ' ajouté');
        }
        
        return $this->redirectToRoute('admin_image_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(Image $image, ImageRepository $imageRepository, Request $request, EntityManagerInterface $em)
    {
        $form_edit = $this->createForm(ImageType::class, $image);

        $newImage = new Image();
        $form_add = $this->createForm(ImageType::class, $newImage);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $image->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Auteur ' . $image->getSrc() . ' modifié');

            return $this->redirectToRoute('admin_image_browse');
        }
        
        return $this->render('admin/image/index.html.twig', [
            'images' => $imageRepository->findAll(),
            'image_edit' => $image,
            'form_add' => $form_add->createView(),
            'form_edit' => $form_edit->createView(),
        ]);   
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}), methods={"DELETE"})
     */
    public function delete(Image $image, Request $request, EntityManagerInterface $em)
    {
        if($request->headers->get('referer') !== null) {

            $em->remove($image);
            $em->flush();

            $this->addFlash('success', 'Auteur ' . $image->getSrc() . ' supprimé');
        }
        
        return $this->redirectToRoute('admin_image_browse');
    }
}
