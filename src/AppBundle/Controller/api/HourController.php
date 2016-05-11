<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Hour;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class HourController extends FOSRestController
{
    /**
     * @param Request $request
     * @return array
     * @View()
     */
    public function postHoursAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $hour=new Hour($data['name']);
        $em=$this->getDoctrine()->getManager();

        $em->persist($hour);
        $em->flush();
    }

    /**
     * @param Hour $hour
     * @return array
     * @View()
     * @ParamConverter("faculty",class="AppBundle:Faculty")
     */
    public function getHourAction(Hour $hour){
        $view = $this->view($hour, 200)
            ->setTemplateVar('hour')
        ;

        return $this->handleView($view);
    }
}
