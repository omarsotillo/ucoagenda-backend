<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Degree;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DegreeController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Degree",
     *  description="Get an array of the degrees within the app",
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
    public function getDegreesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $degrees = $this->getDoctrine()->getRepository("AppBundle:Degree")->findAll();
        $view = $this->view($degrees, 200)
            ->setTemplateVar('degrees');
        // ->setSerializationContext(SerializationContext::create()->setGroups(array('list')));

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Degree",
     *  description="Get a specific degree",
     *  statusCodes={
     *      200="Returned when a degree is received",
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
     * @param Degree $degree
     * @return array
     * @View()
     * @ParamConverter("degree",class="AppBundle:Degree")
     */
    public function getDegreeAction(Degree $degree)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $view = $this->view($degree, 200)
            ->setTemplateVar('degree');
        // ->setSerializationContext(SerializationContext::create()->setGroups(array('detail')));

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Degree",
     *  description="Post a degree",
     *  statusCodes={
     *      202="Returned when a degree is created",
     *      401="Unauthorized",
     *      400="Not valid request",
     *  },
     *  parameters={
     *      {"name"="faculty_id", "dataType"="integer","required"=true,"description"="Faculty parent of the degree"},
     *      {"name"="name", "dataType"="string","required"=true,"description"="Name of the degree"},
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Authorization token used for update the user"
     *         }
     *     }
     * )
     * @View()
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postDegreeAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $new_id = $request->request->get('faculty_id', null);
        $name = $request->request->get('name', null);

        if (isset($new_id, $name)) {
            $faculty = $this->getDoctrine()->getRepository('AppBundle:Faculty')->find($new_id);
            $degree = new Degree();
            $degree->setFaculty($faculty);
            $degree->setName($name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($degree);
            $em->flush();
            $view = $this->view($degree, 202)->setTemplateVar('degrees');
        } else {
            $view = $this->view()
                ->setHeader('error', 'Not good body of sending the faculty')
                ->setStatusCode(400);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Degree",
     *  description="Delete a degree",
     *  statusCodes={
     *      200="Returned when deleted a degree",
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
     * @param Degree $degree
     * @ParamConverter("degree",class="AppBundle:Degree")
     */
    public function deleteDegreeAction(Degree $degree)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($degree);
        $em->flush();
    }
}
