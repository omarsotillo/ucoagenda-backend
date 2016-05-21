<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Lesson;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LessonController extends FOSRestController
{
    /**
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
     * @param Request $request
     * @return array
     * @View()
     */
    public function postLessonAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $degree_id = $request->request->get('degree_id', null);
        $name = $request->request->get('name', null);
        $year = $request->request->get('year', null);
        $quarter = $request->request->get('quarter', null);

        if (isset($degree_id) && isset($name)) {
            $em = $this->getDoctrine()->getManager();

            $degree = $this->getDoctrine()->getRepository('AppBundle:Degree')->find($degree_id);
            $lesson = new Lesson();
            $lesson->setDegree($degree);
            $lesson->setName($name);
            $lesson->setQuarter($quarter);
            $lesson->setYear($year);

            $em->persist($lesson);
            $em->flush();

            $data = array('Lesson added' => $lesson, 'status message' => "Added correctly the lesson");
            $view = $this->view()->setStatusCode(200)->setData($data);
        } else {
            $data = array('Status_Code' => "Error adding the lesson");
            $view = $this->view()->setStatusCode(401) > setData($data);
        }

        return $this->handleView($view);

    }

    /**
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

        return $this->handleView($this->view()->setStatusCode(200)->setHeader('Status', "Deleted correctly"));
    }
}
