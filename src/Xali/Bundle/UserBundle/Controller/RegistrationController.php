<?php

namespace Xali\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Xali\Bundle\UserBundle\Form\UserType;
use Xali\Bundle\UserBundle\Entity\User;

/**
 * Controller managing the registration
 *
 * @author Anthony Bocci <boccianthony@yahoo.fr>
 */
class RegistrationController extends Controller
{
    /**
     * Manage registration
     */
    public function registerAction()
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $request = $this->get('request');
        $entityManager = $this->getDoctrine()->getManager();
        
        //If form is submitted
        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                /*
                 * Put firstname and lastname in lowercase, then put first char
                 * in uppercase
                 */
                $user->setFirstname(ucfirst(strtolower($user->getFirstname())));
                $user->setLastname(ucfirst(strtolower($user->getLastname())));
                $data->setPassword(
                    $this->container->get("security.encoder_factory")
                        ->getEncoder(
                                $data)->encodePassword(
                                    $data->getPlainPassword(), $data->getSalt()
                                )
                        );
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }
        
        return $this->render(
                    'XaliUserBundle:Registration:register.html.twig',
                    array('form' => $form->createView())
                );        
    }
}
