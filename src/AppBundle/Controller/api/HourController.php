<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Hour;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HourController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Hour",
     *  description="Post a hour",
     *  statusCodes={
     *      202="Returned when a hour is created",
     *      401="Unauthorized",
     *      400="Not valid request",
     *  },
     *  parameters={
     *      {"name"="startHour", "dataType"="string","required"=true,"description"="Start hour of a lesson. "},
     *      {"name"="finishHour", "dataType"="string","required"=true,"description"="Finish hour of a lesson"},
     *      {"name"="duration", "dataType"="integer","required"=true,"description"="Duration of a lesson"},
     *      {"name"="classroom", "dataType"="string","required"=true,"description"="Classroom of a lesson"},
     *      {"name"="theory", "dataType"="boolean","required"=true,"description"="Theory of a lesson"},
     *      {"name"="id_lesson", "dataType"="integer","required"=true,"description"="ID of a lesson"},
     *      {"name"="weekday", "dataType"="integer","required"=true,"description"="Week day of the lesson"}
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
            $view = $this->view()->setStatusCode(202)->setData($array);

        } else {

            $view = $this->view()->setStatusCode(400)->setHeader('error', 'Error inserting a new hour');
        }
        return $this->handleView($view);
    }

}
