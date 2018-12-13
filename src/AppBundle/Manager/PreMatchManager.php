<?php

namespace AppBundle\Manager;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\ConnectionRequestRepository;
use AppBundle\Entity\Municipality;
use AppBundle\Entity\PreMatch;
use AppBundle\Entity\User;
use AppBundle\Entity\UserRepository;
use Doctrine\ORM\EntityManager;

class PreMatchManager
{
    /**
     * @var ConnectionRequestRepository
     */
    private $connectionRequestRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @param ConnectionRequestRepository $connectionRequestRepository
     * @param UserRepository $userRepository
     * @param EntityManager $entityManager
     * @param \DateTime $dateTime
     */
    public function __construct(
        ConnectionRequestRepository $connectionRequestRepository,
        UserRepository $userRepository,
        EntityManager $entityManager,
        \DateTime $dateTime
    )
    {
        $this->connectionRequestRepository = $connectionRequestRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->dateTime = $dateTime;
    }

    /**
     * @param Municipality $municipality
     */
    public function createMatches(Municipality $municipality)
    {
        $connectionRequests = $this->connectionRequestRepository
            ->findWantToLearnStartFriendsByMunicipality($municipality);
        foreach ($connectionRequests as $connectionRequest) {
            if (!$connectionRequest->getConnection() && !$connectionRequest->getLearnerPreMatch() && !$connectionRequest->getFluentSpeakerPreMatch()) {
                $preMatch = new PreMatch();
                $preMatch->setLearnerConnectionRequest($connectionRequest);
                $preMatch->setMunicipality($connectionRequest->getMunicipality());

                $this->createMatchForConnectionRequest($connectionRequest, $preMatch);
                $this->entityManager->persist($preMatch);
                $this->entityManager->flush(); // Must flush for not finding same fluent speaker connection request again
            }
        }
    }

    /**
     * @param ConnectionRequest $connectionRequest
     * @param PreMatch $preMatch
     */
    public function createMatchForConnectionRequest(ConnectionRequest $connectionRequest, PreMatch $preMatch)
    {
        $matchArray = $this->userRepository->findMatchArray(
            $connectionRequest->getUser(),
            $connectionRequest,
            [
                'type' => $connectionRequest->getType(),
                'municipality_id' => $connectionRequest->getMunicipality()->getId(),
                'inspected' => true,
            ]
        );

        foreach ($matchArray as $row) {
            /** @var User $user */
            $user = $this->userRepository->find($row['id']);
            foreach ($preMatch->getPreMatchIgnores() as $preMatchIgnore) {
                if ($preMatchIgnore->getFluentSpeaker() == $user) {
                    continue 2;
                }
            }
            $fluentSpeakerConnectionRequest = null;

            foreach ($user->getConnectionRequests() as $loopedConnectionRequest) {
                if ($loopedConnectionRequest->getConnection()) {
                    continue;
                }
                if (
                    $loopedConnectionRequest->getType() == $connectionRequest->getType()
                    && $loopedConnectionRequest->getWantToLearn() == false
                ) {
                    $fluentSpeakerConnectionRequest = $loopedConnectionRequest;
                    break;
                }
            }

            $this->entityManager->refresh($fluentSpeakerConnectionRequest);
            if ($fluentSpeakerConnectionRequest) {
                if ($fluentSpeakerConnectionRequest->getFluentSpeakerPreMatch()) {
                    continue;
                }
                $preMatch->setFluentSpeakerConnectionRequest($fluentSpeakerConnectionRequest);
                return;
            }
        }
    }
}
