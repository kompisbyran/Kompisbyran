<?php

namespace AppBundle\Command;

use AppBundle\Entity\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMeetAgainEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:send-meet-again-emails')
            ->setDescription('Send meet again emails to users without connection requests.')
            ->addArgument('daysSinceCreated', InputArgument::REQUIRED, 'Number of days since the meeting was created.')
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
        $daysSinceCreated = $input->getArgument('daysSinceCreated');

        $createdAt = new \DateTime();
        $createdAt->modify(sprintf('-%s days', $daysSinceCreated));

        $output->writeln(sprintf(
            'Sending emails to users with connections created %s.',
            $createdAt->format('Y-m-d')
        ));

        /** @var Connection[] $connections */
        $connections = $this->getContainer()->get('connection_repository')->findForMeetAgain($createdAt);
        foreach ($connections as $connection) {
            $mostRecentConnectionRequest = $connection->getFluentSpeaker()->getMostRecentConnectionRequest();
            if ($mostRecentConnectionRequest && $mostRecentConnectionRequest->getCreatedAt() < $createdAt) {
                $output->writeln(sprintf('Sending email to %s.', $connection->getFluentSpeaker()->getEmail()));
                $this->getContainer()->get('app.user_mailer')->sendMeetAgainMessage($connection);
            }
        }

        return 0;
    }
}
