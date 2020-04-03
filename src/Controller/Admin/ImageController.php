<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
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
            'images' => $imageRepository->findBy(
                [],
                ['album'=>'ASC', 'priority'=>'ASC']
            ),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader)
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form['file']->getData();
            $fileName = $fileUploader->uploadFile($file);

            if ( $fileName ) {
                $image->setSrc($fileName);
            }

            $em->persist($image);
            $em->flush();

            $this->addFlash('success', 'Image "' . $image->getSrc() . '" ajoutée');
        }
        
        return $this->redirectToRoute('admin_image_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(
        Image $image,
        ImageRepository $imageRepository,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader)
    {
        $form_edit = $this->createForm(ImageType::class, $image);

        $newImage = new Image();
        $form_add = $this->createForm(ImageType::class, $newImage);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $image->setUpdatedAt(new \DateTime());

            $file = $form_edit['file']->getData();

            if ($file) {
                $fileName = $fileUploader->uploadFile($file);

                if ( $fileName ) {
                    $fileUploader->deleteUploadedFile($image->getSrc());
                    $image->setSrc($fileName);
                }
            }   


            $em->flush();

            $this->addFlash('success', 'Image "' . $image->getSrc() . '" modifiée');

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
    public function delete(
        Image $image,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader)
    {
        if($request->headers->get('referer') !== null) {

            $fileUploader->deleteUploadedFile($image->getSrc());
            $em->remove($image);
            $em->flush();

            $this->addFlash('success', 'Image "' . $image->getSrc() . '" supprimée');
        }
        
        return $this->redirectToRoute('admin_image_browse');
    }
}
