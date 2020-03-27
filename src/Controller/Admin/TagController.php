<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tag", name="admin_tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("s", name="browse")
     */
    public function browse(TagRepository $tagRepository)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        return $this->render('admin/tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tag = $form->getData();

            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Etiquette ' . $tag->getTitle() . ' ajoutée');
        }
        
        return $this->redirectToRoute('admin_tag_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(Tag $tag, TagRepository $tagRepository, Request $request, EntityManagerInterface $em)
    {
        $form_edit = $this->createForm(TagType::class, $tag);

        $newTag = new Tag();
        $form_add = $this->createForm(TagType::class, $newTag);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $em->flush();

            $this->addFlash('success', 'Etiquette ' . $tag->getTitle() . ' modifiée');

            return $this->redirectToRoute('admin_tag_browse');
        }
        
        return $this->render('admin/tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
            'tag_edit' => $tag,
            'form_add' => $form_add->createView(),
            'form_edit' => $form_edit->createView(),
        ]);   
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}), methods={"DELETE"})
     */
    public function delete(Tag $tag, Request $request, EntityManagerInterface $em)
    {
        if($request->headers->get('referer') !== null) {

            $em->remove($tag);
            $em->flush();

            $this->addFlash('success', 'Etiquette ' . $tag->getTitle() . ' supprimée');
        }
        
        return $this->redirectToRoute('admin_tag_browse');
    }
}
