<?php

namespace AppBundle\EventListener;

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
            $connection->addFluentSpeakerMeetingStatusEmailSentAtDate(new \DateTime());
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
        if ($connection->getLearner() == $user) {
            $connection->addLearnerMeetingStatusEmailSentAtDate(new \DateTime());
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
    }

}
