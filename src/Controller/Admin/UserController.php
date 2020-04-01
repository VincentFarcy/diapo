<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("s", name="browse")
     */
    public function browse(UserRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'form_add' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();

            $encodedPassword = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Accès de "' . $user->getName() . '" ajouté');
        }
        
        return $this->redirectToRoute('admin_user_browse');
    }

    /**
     * @Route("/{id}/edit", name="edit", requirements={"id": "\d+"}))
     */
    public function edit(
        User $user,
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $form_edit = $this->createForm(UserType::class, $user);

        $newUser = new User();
        $form_add = $this->createForm(UserType::class, $newUser);

        $form_edit->handleRequest($request);
        if ($form_edit->isSubmitted() && $form_edit->isValid()) {

            $password = $form_edit->get('password')->getData();

            if ($password !== null) {
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            $user->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Accès de "' . $user->getName() . '" modifié');

            return $this->redirectToRoute('admin_user_browse');
        }
        
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'user_edit' => $user,
            'form_add' => $form_add->createView(),
            'form_edit' => $form_edit->createView(),
        ]);   
    }

    /**
     * @Route("/{id}/delete", name="delete", requirements={"id": "\d+"}), methods={"DELETE"})
     */
    public function delete(User $user, Request $request, EntityManagerInterface $em)
    {
        if($request->headers->get('referer') !== null) {

            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Accès de "' . $user->getName() . '" supprimé');
        }
        
        return $this->redirectToRoute('admin_user_browse');
    }}
