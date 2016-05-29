<?php

namespace BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use BlogBundle\Entity\Post;

/**
 * @Route("/user/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/read/{id}")
     * @Template()
     */
    public function readAction(Post $post)
    {
        return array('post' => $post);
    }

    /**
     * @Route("/update/{id}")
     * @Route("/create", name="blog_post_create")
     * @Template()
     */
    public function updateAction(Request $request, Post $post = null)
    {
        if (!$post) $post = new Post();
        $form = $this->createForm('BlogBundle\Form\PostType', $post);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $user = $this->getUser();
                $post->setUser($user);

                $em->persist($post);
                $em->flush();

                $this->addFlash('success', 'Article ajouté ou modifié avec succès !');

                return $this->redirectToRoute('app_app_index');
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/delete/{id}")
     */
    public function deleteAction(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_app_index');
    }
}
