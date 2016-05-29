<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use UserBundle\Entity\User;

class UserController extends Controller
{
    /**
     * @Route("/register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) return $this->redirectToRoute('app_app_index');

        $user = new User();
        $form = $this->createForm('UserBundle\Form\RegisterType', $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                // On hash le mdp avec le salt de l'utilisateur
                $user->setPassword($this->container->get('security.encoder_factory')
                    ->getEncoder($user)->encodePassword($form->get('password')->getData(), $user->getSalt())
                );

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', 'Inscription faite avec succès, vous pouvez vous connecter !');

                return $this->redirectToRoute('app_app_index');
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/login")
     * @Method({"GET"})
     * @Template()
     */
    public function loginAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) return $this->redirectToRoute('app_app_index');
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return array('last_username' => $lastUsername, 'error' => $error);
    }

    /**
     * @Method({"POST"})
     * @Route("/login_check")
     */
    public function loginCheckAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration');
    }

    /**
     * @Method({"GET"})
     * @Route("/logout")
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration');
    }
}
