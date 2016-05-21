<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Hour;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HourController extends FOSRestController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     * @View()
     */
    public function postHourAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $startHour = $request->request->get('startHour', null);
        $finishHour = $request->request->get('finishHour', null);
        $duration = $request->request->get('duration', null);
        $weekday = $request->request->get('weekday', null);
        $class = $request->request->get('classroom', null);
        $isTheory = $request->request->get('theory', null);
        $id_lesson = $request->request->get('id_lesson', null);

        if (isset($finishHour,$weekday,$duration,$startHour,$class,$isTheory,$id_lesson)) {
            $hour = new Hour();
            $em = $this->getDoctrine()->getManager();
            $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')->find($id_lesson);

            $hour->setClassLocation($class);
            $hour->setDayOfTheWeek($weekday);
            $hour->setDuration($duration);
            $hour->setIsTheory($isTheory);
            $hour->setLesson($lesson);

            $hour->setStartHour($startHour);
            $hour->setFinishHour($finishHour);

            $em->persist($hour);
            $em->flush();

            $array = array('Hour' => $hour, 'Status' => 'Added correctly the hour');
            $view = $this->view()->setStatusCode(204)->setData($array);

        } else {

            $view = $this->view()->setStatusCode(400)->setHeader('error', 'Error inserting a new hour');
        }
        return $this->handleView($view);
    }

    /**
     * @param Hour $hour
     * @return array
     * @View()
     * @ParamConverter("faculty",class="AppBundle:Faculty")
     */
    public function getHourAction(Hour $hour)
    {
        $view = $this->view($hour, 200)
            ->setTemplateVar('hour')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('detail')));

        return $this->handleView($view);
    }
}
