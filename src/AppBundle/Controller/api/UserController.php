<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{
    /**
     * Method to register a new user in the APP.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This is a description of your API method",
     *  filters={
     *      {"name"="a-filter", "dataType"="integer"},
     *      {"name"="another-filter", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}
     *  }
     * )
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

        if (isset($email, $password, $username)) {
            $user->setUsername($username);
            $user->setPlainPassword($password);
            $user->setIsFirstTime(true);
            $user->setEmail($email);
            $user->setEnabled(true);
            $user->setRoles(array('ROLE_USER'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $array = array('user_username' => $user->getUsername(), 'user_email' => $user->getEmail(), 'status' => 'User added correctly');
            $view = $this->view()
                ->setStatusCode(200)
                ->setData($array);

        } else {
            $array = array('status' => 'No user created');
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

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $facultyID = $request->request->get('_facultyId', null);
        $degreeID = $request->request->get('_degreeId', null);
        $id_lessons = $request->request->get('_lessonsId', null);

        if (isset($user)) {

            if (isset($facultyID)) {
                $faculty = $this->getDoctrine()->getRepository('AppBundle:Faculty')->find($facultyID);
                $user->setFaculty($faculty);
            }
            if (isset($degreeID)) {
                $degree = $this->getDoctrine()->getRepository('AppBundle:Degree')->find($degreeID);
                $user->setDegree($degree);
            }
            foreach ($id_lessons as $id_lesson) {
                $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')->find($id_lesson);
                if (isset($lesson)) {
                    $user->addLesson($lesson);
                }
            }
            $user->setIsFirstTime(false);
            $view = $this->view()
                ->setStatusCode(200)
                ->setData($user);
            $em->flush();
        } else {
            $view = $this->view()
                ->setStatusCode(400);
        }
        return $this->handleView($view);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @View()
     * @Post("/api/check_token")
     */
    public function checkToken()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (isset($user)) {
            $data = array('status' => 'Token is valid', 'username' => $user->getUsername(), 'email' => $user->getEmail(), 'isFirstTime' => $user->getIsFirstTime(), 'roles' => $user->getRoles(), 'enabled' => $user->isEnabled(), 'lessons' => $user->getLessons());
            $view = $this->view()
                ->setStatusCode(200)
                ->setHeader('status', 'There is a user in this token')
                ->setData($data);
        } else {
            $data = array('Error' => 'Token is not valid');
            $view = $this->view()->setStatusCode(401)->setData($data);
        }

        return $this->handleView($view);
    }

}
