<?php

namespace AppBundle\Controller\Admin;

use AppBundle\DomainEvents;
use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Event\ConnectionCreatedEvent;
use AppBundle\Form\ConnectionRequestType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @Route("admin")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_start")
     */
    public function indexAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $learnerConnectionRequest = $this->getConnectionRequestRepository()->find($request->request->getInt('learner'));
            $fluentSpeakerConnectionRequest = $this->getConnectionRequestRepository()->find($request->request->getInt('fluentSpeaker'));

            $connection = new Connection();
            $connection->setLearner($learnerConnectionRequest->getUser());
            $connection->setFluentSpeaker($fluentSpeakerConnectionRequest->getUser());
            $connection->setCity($learnerConnectionRequest->getCity());

            $manager->persist($connection);
            $manager->remove($learnerConnectionRequest);
            $manager->remove($fluentSpeakerConnectionRequest);
            $manager->flush();

            $this->get('event_dispatcher')->dispatch(
                DomainEvents::CONNECTION_CREATED,
                new ConnectionCreatedEvent($connection)
            );

            return $this->redirect($this->generateUrl('admin_start'));
        }

        $learners = $this->getConnectionRequestRepository()->findBy(['wantToLearn' => true]);
        $fluentSpeakers = $this->getConnectionRequestRepository()->findBy(['wantToLearn' => false]);

        $parameters = [
            'learners' => $learners,
            'fluentSpeakers' => $fluentSpeakers,
        ];

        return $this->render('admin/default/index.html.twig', $parameters);
    }

    protected function getConnectionRequestRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:ConnectionRequest');
    }

}
