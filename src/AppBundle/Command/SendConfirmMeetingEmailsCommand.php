<?php

namespace AppBundle\Command;

use AppBundle\Entity\Connection;
use AppBundle\Enum\MeetingTypes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendConfirmMeetingEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:send-confirm-meeting-emails')
            ->setDescription('Send meeting status emails to users related to a connection.')
            ->addArgument('daysSinceCreated', InputArgument::REQUIRED, 'Number of days since the connection was created.')
            ->addArgument('previousMailsCount', InputArgument::REQUIRED, 'Number of previous sent emails.')
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
        $statuses = [MeetingTypes::UNKNOWN, MeetingTypes::NOT_YET_MET];

        $daysSinceCreated = $input->getArgument('daysSinceCreated');
        $previousMailsCount = $input->getArgument('previousMailsCount');

        $createdAt = new \DateTime();
        $createdAt->modify(sprintf('-%s days', $daysSinceCreated));

        $output->writeln(sprintf(
            'Sending emails to users with connections created %s and has received %s mails erlier.',
            $createdAt->format('Y-m-d'),
            $previousMailsCount
        ));

        /** @var Connection[] $connections */
        $connections = $this->getContainer()->get('connection_repository')->findForMeetingConfirmation($createdAt, $previousMailsCount);
        foreach ($connections as $connection) {
            $output->writeln(sprintf('Sending for connection created %s', $connection->getCreatedAt()->format('Y-m-d H:i:s')));
            if (in_array($connection->getFluentSpeakerMeetingStatus(), $statuses)) {
                if ($connection->getFluentSpeakerMeetingStatusEmailsCount() == $previousMailsCount) {
                    if (!$connection->getFluentSpeaker()->isEnabled()) {
                        $output->writeln(sprintf('<error> - %s is not enabled</error>', $connection->getFluentSpeaker()->getEmail()));
                        continue;
                    }
                    $output->writeln(sprintf(' - %s', $connection->getFluentSpeaker()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendConfirmMeetingMessage(
                        $connection->getFluentSpeaker(),
                        $connection
                    );
                    sleep(2);
                }
            }
            if (in_array($connection->getLearnerMeetingStatus(), $statuses)) {
                if ($connection->getLearnerMeetingStatusEmailsCount() == $previousMailsCount) {
                    if (!$connection->getLearner()->isEnabled()) {
                        $output->writeln(sprintf('<error> - %s is not enabled</error>', $connection->getLearner()->getEmail()));
                        continue;
                    }
                    $output->writeln(sprintf(' - %s', $connection->getLearner()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendConfirmMeetingMessage(
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
