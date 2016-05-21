<?php

namespace AppBundle\Controller\api;

use AppBundle\Entity\Faculty;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FacultyController extends FOSRestController
{
    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Faculty",
     *  description="Get an array of the faculties within the app",
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
     * @throws AccessDeniedException
     * @View(serializerEnableMaxDepthChecks=true)
     */
    public function getFacultiesAction()
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $faculties = $this->getDoctrine()->getRepository("AppBundle:Faculty")->findAll();
        if (isset($faculties)) {
            $view = $this->view($faculties, 200)
                ->setTemplateVar('faculties');
            //->setSerializationContext(SerializationContext::create()->setGroups(array('list')));
        } else {
            $view = $this->view()
                ->setStatusCode(404)
                ->setHeader('Error', 'No values found')
                ->setTemplateVar('faculties');
        }
        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Faculty",
     *  description="Get a specific faculty",
     *  statusCodes={
     *      200="Returned when a faculty is received",
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
     * @param Faculty $faculty
     * @return array
     * @View()
     * @ParamConverter("faculty",class="AppBundle:Faculty")
     */
    public function getFacultyAction(Faculty $faculty)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $view = $this->view($faculty, 200)
            ->setTemplateVar('faculty');
        // ->setSerializationContext(SerializationContext::create()->setGroups(array('detail')));

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Faculty",
     *  description="Post a faculty",
     *  statusCodes={
     *      202="Returned when a faculty is created",
     *      401="Unauthorized",
     *      400="Not valid request",
     *  },
     *  parameters={
     *      {"name"="name", "dataType"="string","required"=true,"description"="Name of the lesson"},
     *      {"name"="location", "dataType"="string","required"=true,"description"="Location of the faculty"},
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
     * @View()
     */
    public function postFacultyAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $name = $request->request->get('name', null);
        $location = $request->request->get('location', null);

        if (isset($name, $location)) {
            $faculty = new Faculty();
            $faculty->setName($name);
            $faculty->setLocation($location);

            $em = $this->getDoctrine()->getManager();
            $em->persist($faculty);
            $em->flush();

            $view = $this->view($faculty)
                ->setStatusCode(200)
                ->setTemplateVar('faculty');

        } else {
            $view = $this->view()
                ->setHeader('Error', 'Not a good way to be inserted check the json values')
                ->setStatusCode(400);
        }

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  section="Faculty",
     *  description="Delete a faculty",
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
     * @param Faculty $faculty
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @ParamConverter("faculty", class="AppBundle:Faculty")
     * @View()
     */
    public function deleteFacultyAction(Faculty $faculty)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($faculty);
        $em->flush();

        $view = $this->view($faculty, 200)->setTemplateVar('faculty_deleted');
        return $this->handleView($view);
    }
}
