<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Lesson;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LessonController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Lesson",
     *  description="Get an array of the lessons within the app",
     *  statusCodes={
     *      200="Returned when all values are returned",
     *      401="Unauthorized"
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
     * @return array
     * @View()
     */
    public function getLessonsAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $lessons = $this->getDoctrine()->getRepository("AppBundle:Lesson")->findAll();
        $view = $this->view($lessons, 200)
            ->setTemplateVar('lessons');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Lesson",
     *  description="Post a lesson",
     *  statusCodes={
     *      202="Returned when a lesson is created",
     *      401="Unauthorized",
     *      400="Not valid request",
     *  },
     *  parameters={
     *      {"name"="degree_id", "dataType"="integer","required"=true,"description"="degree id of the lesson. Parent info"},
     *      {"name"="name", "dataType"="string","required"=true,"description"="Name of the lesson"},
     *      {"name"="year", "dataType"="integer","required"=true,"description"="Year of the lesson"},
     *      {"name"="quarter", "dataType"="integer","required"=true,"description"="Quarter of the lesson"}
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Authorization token used for update the user"
     *         }
     *     }
     * )
     * @param Request $request
     * @return array
     * @View()
     */
    public function postLessonAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $degree_id = $request->request->get('degree_id', null);
        $name = $request->request->get('name', null);
        $year = $request->request->get('year', null);
        $quarter = $request->request->get('quarter', null);

        if (isset($degree_id, $name)) {
            $em = $this->getDoctrine()->getManager();

            $degree = $this->getDoctrine()->getRepository('AppBundle:Degree')->find($degree_id);

            $lesson = new Lesson();
            $lesson->setDegree($degree);
            $lesson->setName($name);
            $lesson->setQuarter($quarter);
            $lesson->setYear($year);

            $em->persist($lesson);
            $em->flush();

            $data = array('Lesson added' => $lesson, 'status message' => 'Added correctly the lesson');
            $view = $this->view()->setStatusCode(202)->setData($data);
        } else {
            $data = array('Status_Code' => 'Error adding the lesson');
            $view = $this->view()->setStatusCode(400)->setData($data);
        }

        return $this->handleView($view);

    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Lesson",
     *  description="Get a specific lesson of a user",
     *  statusCodes={
     *      200="Returned when a lesson is received",
     *      401="Unauthorized"
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
     * @param Lesson $lesson
     * @return array
     * @View()
     * @ParamConverter("lesson",class="AppBundle:Lesson")
     */
    public function getLessonAction(Lesson $lesson)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $view = $this->view($lesson, 200)
            ->setTemplateVar('lesson');

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Lesson",
     *  description="Delete a lesson",
     *  statusCodes={
     *      200="Returned when deleted a lesson",
     *      401="Unauthorized"
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
     * @param Lesson $lesson
     * @View()
     * @ParamConverter("lesson",class="AppBundle:Lesson")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteLessonAction(Lesson $lesson)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();

        $em->remove($lesson);
        $em->flush();

        return $this->handleView($this->view()->setStatusCode(200)->setHeader('Status', 'Deleted correctly'));
    }
}
