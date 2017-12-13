<?php

namespace AppBundle\EventListener;

use AppBundle\Enum\MeetingTypes;
use AppBundle\Event\MeetingStatusUpdatedEvent;
use Doctrine\ORM\EntityManagerInterface;

class IncrementConfirmationEmailCountListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param MeetingStatusUpdatedEvent $event
     */
    public function whenEmailIsSent(MeetingStatusUpdatedEvent $event)
    {
        $user = $event->getUser();
        $connection = $event->getConnection();

        if ($connection->getFluentSpeaker() == $user) {
            $connection->setFluentSpeakerMeetingStatusEmailsCount(
                $connection->getFluentSpeakerMeetingStatusEmailsCount() + 1
            );
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
        if ($connection->getLearner() == $user) {
            $connection->setLearnerMeetingStatusEmailsCount(
                $connection->getLearnerMeetingStatusEmailsCount() + 1
            );
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
    }

}
