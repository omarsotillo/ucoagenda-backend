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
     *  section="User",
     *  description="Register new users in the app",
     *  parameters={
     *      {"name"="_username", "dataType"="string","required"=true,"description"="Username of the user"},
     *      {"name"="_password", "dataType"="string","required"=true,"description"="Password of the user"},
     *      {"name"="_email", "dataType"="string","required"=true,"description"="Email of the user"}
     *  },
     *  statusCodes={
     *      201="Returned when successful created a user",
     *      400="Returned when not created correctly the user"
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
                ->setStatusCode(201)
                ->setData($array);

        } else {
            $array = array('status' => 'No user created');
            $view = $this->view()
                ->setStatusCode(400)
                ->setData($array);
        }
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="User",
     *  description="Update the user of the token received",
     *  statusCodes={
     *      200="Returned when updated a user",
     *      400="Returned when not updated correctly the user"
     *  },
     *  parameters={
     *      {"name"="token", "dataType"="string","required"=true,"description"="Header token of the app"}
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Authorization token used for update the user"
     *         }
     *     }
     * )
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $id
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
     * @ApiDoc(
     *  resource=true,
     *  section="Token",
     *  description="Check a token parameter and return the user value of the user",
     *  parameters={
     *      {"name"="token", "dataType"="string","required"=true,"description"="Header token of the app"}
     *  },
     *  statusCodes={
     *      200="Returned the user value and received correctly",
     *      401="Unauthorized token"
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Authorization token used for update the user"
     *         }
     *     }
     * )
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
