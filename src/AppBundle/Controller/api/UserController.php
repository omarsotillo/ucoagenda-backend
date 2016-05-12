<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{
    /**
     * @param Request $request
     * @View()
     * @Post("/api/register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerUser(Request $request)
    {
        $user = new User();
        $username = $request->request->get('_username', null);
        $password = $request->request->get('_password', null);
        $email = $request->request->get('_email', null);


        if (isset($email) && isset($password) && isset($username)) {
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setIsFirstTime(true);
            $user->setEmail($email);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $array = array('user_username' => $user->getUsername(),'user_email' => $user->getEmail(), 'status' => "User added correctly");
            $view = $this->view()
                ->setStatusCode(200)
                ->setData($array);

        } else {
            $array = array('status' => "No user created");
            $view = $this->view()
                ->setStatusCode(404)
                ->setData($array);
        }
        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @View()
     * @Put("/api/user/{id}")
     */
    public function updateUser(Request $request, $id){
        $user=$this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array('id'=>$id));


    }

}
