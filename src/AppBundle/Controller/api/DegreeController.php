<?php

namespace AppBundle\Controller\api;


use AppBundle\Entity\Degree;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class DegreeController extends FOSRestController
{
    /**
     * @return array
     * @View()
     */
    public function getDegreesAction()
    {
        $degrees = $this->getDoctrine()->getRepository("AppBundle:Degree")->findAll();
        $view = $this->view($degrees, 200)
            ->setTemplateVar('degrees')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('list')));

        return $this->handleView($view);
    }

    /**
     * @param Degree $degree
     * @return array
     * @View()
     * @ParamConverter("degree",class="AppBundle:Degree")
     */
    public function getDegreeAction(Degree $degree)
    {
        $view = $this->view($degree, 200)
            ->setTemplateVar('degree')
            ->setSerializationContext(SerializationContext::create()->setGroups(array('detail')));

        return $this->handleView($view);
    }

    /**
     * @View()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDegreeAction(Request $request)
    {
        $new_id = $request->request->get('faculty_id', null);
        $name = $request->request->get('name', null);

        if (isset($new_id) && isset($name)) {
            $faculty = $this->getDoctrine()->getRepository('AppBundle:Faculty')->find($new_id);
            $degree = new Degree();
            $degree->setFaculty($faculty);
            $degree->setName($name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($degree);
            $em->flush();
            $view = $this->view($degree, 200)->setTemplateVar("degrees");
        } else {
            $view = $this->view()
                ->setHeader("error", "Not good way of sending the files")
                ->setStatusCode(400);
        }

        return $this->handleView($view);
    }

    /**
     * @param Degree $degree
     * @ParamConverter("degree",class="AppBundle:Degree")
     */
    public function deleteDegreeAction(Degree $degree)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($degree);
        $em->flush();
    }
}
