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
            if (!$connectionRequest->getLearnerPreMatch() && !$connectionRequest->getFluentSpeakerPreMatch()) {
                $this->createMatchForConnectionRequest($connectionRequest, new PreMatch());
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
                $preMatch->setLearnerConnectionRequest($connectionRequest);
                $preMatch->setFluentSpeakerConnectionRequest($fluentSpeakerConnectionRequest);
                $preMatch->setMunicipality($connectionRequest->getMunicipality());
                $this->entityManager->persist($preMatch);
                $this->entityManager->flush();
                return;
            }
        }
    }

    /**
     * @param PreMatch $preMatch
     *
     * @return string
     */
    public function getMeetingTime(PreMatch $preMatch)
    {
        $meetingDay = clone $this->dateTime;
        $meetingDay->add(new \DateInterval('P7D'));

        $weekday = $preMatch->getFluentSpeakerConnectionRequest()->isAvailableWeekday()
            && $preMatch->getLearnerConnectionRequest()->isAvailableWeekday();
        $weekend = $preMatch->getFluentSpeakerConnectionRequest()->isAvailableWeekend()
            && $preMatch->getLearnerConnectionRequest()->isAvailableWeekend();
        $daytime = $preMatch->getFluentSpeakerConnectionRequest()->isAvailableDay()
            && $preMatch->getLearnerConnectionRequest()->isAvailableDay();
        $evening = $preMatch->getFluentSpeakerConnectionRequest()->isAvailableEvening()
            && $preMatch->getLearnerConnectionRequest()->isAvailableEvening();

        if ($weekday) {
            if ((int) $meetingDay->format('N') > 5) {
                $meetingDay->modify('next weekday');
            }
        } elseif ($weekend) {
            if ((int) $meetingDay->format('N') <= 5) {
                $meetingDay->modify('next saturday');
            }
        }

        $day = sprintf('%s %s', $this->getWeekday($meetingDay), $meetingDay->format('Y-m-d'));

        $time = '';
        if ($daytime) {
            $time = 'klockan 12';
        } elseif ($evening) {
            $time = 'klockan 18';
        }

        return trim(sprintf('%s %s', $day, $time));
    }

    /**
     * @param \DateTime $datetime
     *
     * @return string
     */
    private function getWeekday(\DateTime $datetime)
    {
        $dayOfWeek = (int) $datetime->format('N');
        switch ($dayOfWeek) {
            case 1:
                return 'Måndag';
            case 2:
                return 'Tisdag';
            case 3:
                return 'Onsdag';
            case 4:
                return 'Torsdag';
            case 5:
                return 'Fredag';
            case 6:
                return 'Lördag';
            case 7:
                return 'Söndag';
        }
    }
}
