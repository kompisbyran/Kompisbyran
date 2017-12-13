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
            ->setDescription('Send follow up emails to users related to a connection.')
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
        $connections = $this->getContainer()->get('connection_repository')->findForMeetingFollowUp($createdAt, $previousMailsCount);
        foreach ($connections as $connection) {
            $output->writeln(sprintf('Sending for connection created %s', $connection->getCreatedAt()->format('Y-m-d H:i:s')));
            if (in_array($connection->getFluentSpeakerMeetingStatus(), $statuses)) {
                if ($connection->getFluentSpeakerMeetingStatusEmailsCount() == $previousMailsCount) {
                    $output->writeln(sprintf(' - %s', $connection->getFluentSpeaker()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendConfirmMeetingMessage(
                        $connection->getFluentSpeaker(),
                        $connection
                    );
                }
            }
            if (in_array($connection->getLearnerMeetingStatus(), $statuses)) {
                if ($connection->getLearnerMeetingStatusEmailsCount() == $previousMailsCount) {
                    $output->writeln(sprintf(' - %s', $connection->getLearner()->getEmail()));
                    $this->getContainer()->get('app.user_mailer')->sendConfirmMeetingMessage(
                        $connection->getLearner(),
                        $connection
                    );
                }
            }
        }

        return 0;
    }
}
