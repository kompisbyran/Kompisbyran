<?php

namespace AppBundle\Controller\Open;

use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Enum\MeetingTypes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConnectionController extends Controller
{
    /**
     * @Route("/public/meetings/{uuid}/{id}", name="public_connection")
     */
    public function indexAction($uuid, Connection $connection, Request $request)
    {
        $user = $this->get('user_repository')->findOneBy(['uuid' => $uuid]);
        if (!$user) {
            throw $this->createNotFoundException(
                sprintf('Connection %s not found for user %s.', $connection->getId(), $uuid)
            );
        }

        if ($connection->getFluentSpeaker() != $user && $connection->getLearner() != $user) {
            throw $this->createNotFoundException(
                sprintf('Connection %s not found for user %s.', $connection->getId(), $uuid)
            );
        }

        $alreadyConfirmed = false;
        if ($connection->getFluentSpeaker() == $user && !in_array($connection->getFluentSpeakerMeetingStatus(), $this->changableStatuses())) {
            $alreadyConfirmed = true;
        }
        if ($connection->getLearner() == $user && !in_array($connection->getLearnerMeetingStatus(), $this->changableStatuses())) {
            $alreadyConfirmed = true;
        }

        if ($request->isMethod('POST')) {
            if ($connection->getFluentSpeaker() == $user) {
                $connection->setFluentSpeakerMeetingStatus($request->request->get('status'));
            } else {
                $connection->setLearnerMeetingStatus($request->request->get('status'));
            }
            $errors = $this->get('validator')->validate($connection);
            if ($errors->count() == 0) {
                $this->getDoctrine()->getManager()->persist($connection);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        if ($connection->getFluentSpeaker() == $user) {
            $connectionRequest = $connection->getFluentSpeakerConnectionRequest();
        } else {
            $connectionRequest = $connection->getLearnerConnectionRequest();
        }

        $parameters = [
            'alreadyConfirmed' => $alreadyConfirmed,
            'uuid' => $uuid,
            'connection' => $connection,
            'clone' => $this->clonable($connectionRequest)
        ];

        return $this->render('open/connection.html.twig', $parameters);
    }

    /**
     * @Route("/public/meetings/{uuid}/{id}/clone", name="public_clone_connection_request")
     * @Method("POST")
     */
    public function cloneConnectionRequestAction($uuid, Connection $connection, Request $request)
    {
        $user = $this->get('user_repository')->findOneBy(['uuid' => $uuid]);
        if (!$user) {
            throw $this->createNotFoundException(
                sprintf('Connection %s not found for user %s.', $connection->getId(), $uuid)
            );
        }

        if ($connection->getFluentSpeaker() != $user && $connection->getLearner() != $user) {
            throw $this->createNotFoundException(
                sprintf('Connection %s not found for user %s.', $connection->getId(), $uuid)
            );
        }

        if ($connection->getFluentSpeaker() == $user) {
            $connectionRequest = $connection->getFluentSpeakerConnectionRequest();
        } else {
            $connectionRequest = $connection->getLearnerConnectionRequest();
        }

        if ($this->clonable($connectionRequest)) {
            $newConnectionRequest = clone $connectionRequest;
            $newConnectionRequest->setCreatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->persist($newConnectionRequest);
            $this->getDoctrine()->getManager()->flush();
        }

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @param ConnectionRequest $connectionRequest
     * @return bool
     */
    public function clonable(ConnectionRequest $connectionRequest = null)
    {
        if (!$connectionRequest) {
            return false;
        }

        if ($connectionRequest->getConnection()) {
            return false;
        }

        if (!$connectionRequest->getUser()->hasOpenConnectionRequest()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    private function changableStatuses()
    {
        return [
            MeetingTypes::UNKNOWN,
            MeetingTypes::NOT_YET_MET,
        ];
    }
}
