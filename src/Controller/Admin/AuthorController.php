<?php

namespace App\Controller\Admin;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/author", name="admin_author_")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("s", name="browse")
     */
    public function browse(AuthorRepository $authorRepository)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        return $this->render('admin/author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $author = $form->getData();

            $em->persist($author);
            $em->flush();

            $this->addFlash('success', 'Auteur "' . $author->getFullName() . '" ajouté');
        }
        
        return $this->redirectToRoute('admin_author_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(Author $author, AuthorRepository $authorRepository, Request $request, EntityManagerInterface $em)
    {
        $form_edit = $this->createForm(AuthorType::class, $author);

        $newAuthor = new Author();
        $form_add = $this->createForm(AuthorType::class, $newAuthor);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $author->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Auteur "' . $author->getFullName() . '" modifié');

            return $this->redirectToRoute('admin_author_browse');
        }
        
        return $this->render('admin/author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
            'author_edit' => $author,
            'form_add' => $form_add->createView(),
            'form_edit' => $form_edit->createView(),
        ]);   
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}), methods={"DELETE"})
     */
    public function delete(Author $author, Request $request, EntityManagerInterface $em)
    {
        if($request->headers->get('referer') !== null) {

            $em->remove($author);
            $em->flush();

            $this->addFlash('success', 'Auteur "' . $author->getFullName() . '" supprimé');
        }
        
        return $this->redirectToRoute('admin_author_browse');
    }
}
