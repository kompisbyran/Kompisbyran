<?php

namespace AppBundle\EventListener;

use AppBundle\Event\FollowUpEmailSentEvent;
use Doctrine\ORM\EntityManagerInterface;

class IncrementFollowUpEmail2CountListener
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
     * @param FollowUpEmailSentEvent $event
     */
    public function whenEmailIsSent(FollowUpEmailSentEvent $event)
    {
        $user = $event->getUser();
        $connection = $event->getConnection();

        if ($connection->getFluentSpeaker() == $user) {
            $connection->addFluentSpeakerFollowUpEmail2SentAtDate(new \DateTime());
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
        if ($connection->getLearner() == $user) {
            $connection->addLearnerFollowUpEmail2SentAtDate(new \DateTime());
            $this->entityManager->persist($connection);
            $this->entityManager->flush();
        }
    }

}
