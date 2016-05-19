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
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $array = array('user_username' => $user->getUsername(), 'user_email' => $user->getEmail(), 'status' => "User added correctly");
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
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @Put("/api/user")
     */
    public function updateUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // $user = $em->getRepository('AppBundle:User')->find($id);

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $facultyID = $request->request->get('_facultyId', null);
        $degreeID = $request->request->get('_degreeId', null);

        $view = $this->view();

        if (isset($user)) {

            if (isset($facultyID)) {
                $user->setFaculty($facultyID);
            }
            if (isset($degreeID)) {
                $user->setDegree($degreeID);
            }
            $user->setIsFirstTime(false);
            $em->flush();

            $view->setStatusCode(200);
            $view->setData($user);
        }
        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @View()
     * @Post("/api/check_token")
     */
    public function checkToken(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (isset($user)) {
            $data = array('status' => "Token is valid", 'username' => $user->getUsername(), 'email' => $user->getEmail(), 'isFirstTime' => $user->getIsFirstTime(), 'roles' => $user->getRoles(), 'enabled' => $user->isEnabled());
            $view = $this->view()
                ->setStatusCode(200)
                ->setHeader('status', "There is a user in this token")
                ->setData($data);
        } else {
            $data = array('Error' => "Token is not valid");
            $view = $this->view()->setStatusCode(401)->setData($data);
        }

        return $this->handleView($view);
    }

}
