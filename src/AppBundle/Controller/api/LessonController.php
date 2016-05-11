<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Lesson;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class LessonController extends FOSRestController
{
    /**
     * @return array
     * @View()
     */
    public function getLessonsAction()
    {
        $lessons=$this->getDoctrine()->getRepository("AppBundle:Lesson")->findAll();
        $view = $this->view($lessons, 200)
            ->setTemplateVar('lessons')
        ;

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     * @return array
     * @View()
     */
    public function postLessonAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $lesson=new Lesson($data['name']);
        $em=$this->getDoctrine()->getManager();

        $em->persist($lesson);
        $em->flush();
    }

    /**
     * @param Lesson $lesson
     * @return array
     * @View()
     * @ParamConverter("faculty",class="AppBundle:Faculty")
     */
    public function getLessonAction(Lesson $lesson){
        $view = $this->view($lesson, 200)
            ->setTemplateVar('degrees')
        ;

        return $this->handleView($view);
    }
}
