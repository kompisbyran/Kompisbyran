<?php

namespace AppBundle\Command;

use AppBundle\Entity\Connection;
use AppBundle\Enum\MeetingTypes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendFollowUpEmail3Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:send-follow-up-email3')
            ->setDescription('Send follow up email3 with message to tell Kompisbyrån about the period after the meeting.')
            ->addArgument('daysSinceMarkedAsMet', InputArgument::REQUIRED, 'Number of days since the meeting was marked as held.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daysSinceMarkedAsMet = $input->getArgument('daysSinceMarkedAsMet');

        $createdAt = new \DateTime();
        $createdAt->modify(sprintf('-%s days', $daysSinceMarkedAsMet));

        $output->writeln(sprintf(
            'Sending emails to users with connections marked as met %s.',
            $createdAt->format('Y-m-d')
        ));

        /** @var Connection[] $connections */
        $connections = $this->getContainer()->get('connection_repository')->findForMeetingFollowUpEmail3($createdAt);
        foreach ($connections as $connection) {
            $output->writeln(sprintf('Sending for connection created %s', $connection->getCreatedAt()->format('Y-m-d H:i:s')));
            if ($connection->getFluentSpeakerMeetingStatus() == MeetingTypes::MET) {
                if ($connection->getFluentSpeaker()->isEnabled()) {
                    $output->writeln(sprintf(' - %s', $connection->getFluentSpeaker()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendFollowUpEmail3Message(
                        $connection->getFluentSpeaker(),
                        $connection
                    );
                    sleep(2);
                }
            }
            if ($connection->getLearnerMeetingStatus() == MeetingTypes::MET) {
                if ($connection->getLearner()->isEnabled()) {
                    $output->writeln(sprintf(' - %s', $connection->getLearner()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendFollowUpEmail3Message(
                        $connection->getLearner(),
                        $connection
                    );
                    sleep(2);
                }
            }
        }

        return 0;
    }
}
