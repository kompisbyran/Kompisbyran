<?php

namespace AppBundle\Command;

use AppBundle\Entity\Connection;
use AppBundle\Entity\User;
use AppBundle\Enum\MeetingTypes;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailsToIncompleteUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:send-emails-to-incomplete-users')
            ->setDescription('Send emails to incomplete users with message to fulfill registration')
            ->addArgument('daysSinceCreated', InputArgument::REQUIRED, 'Number of days since the user was created.')
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

        $output->writeln(sprintf('Sending emails to incomplete users created %s.', $createdAt->format('Y-m-d')));

        /** @var User[] $users */
        $users = $this->getContainer()->get('user_repository')->findIncompleteByCreatedDate($createdAt);
        foreach ($users as $user) {
            $output->writeln(sprintf('Sending email to %s', $user->getEmail()));
            $this->getContainer()->get('app.user_mailer')->sendIncompleteUserEmailMessage($user);
            sleep(2);
        }

        return 0;
    }
}
