<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendAboutToBeDeletedEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:send-about-to-be-deleted-emails')
            ->setDescription('Send emails to inactive user telling them their account will be deleted in a few days.')
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
        $date = new \DateTime();
        $date->modify(sprintf('-%s years', $this->getContainer()->getParameter('years_of_inactivity_before_deletion')));

        $filteredUsers = [];

        /** @var User[] $users */
        $users = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class)->findInactiveSince($date);
        foreach ($users as $user) {
            if ($user->getInactiveEmailSentAt() && $user->getInactiveEmailSentAt() > new \DateTime('-1 year')) {
                continue;
            }
            $filteredUsers[] = $user;
        }

        $output->writeln(
            sprintf('Sending emails to %s user(s) inactive since %s.', count($filteredUsers), $date->format('Y-m-d'))
        );

        foreach ($filteredUsers as $user) {
            $this->getContainer()->get('app.user_mailer')->sendUserIsAboutToBeDeletedMessage($user);
            $user->setInactiveEmailSentAt(new \DateTime());
            $this->getContainer()->get('doctrine.orm.entity_manager')->persist($user);
            $this->getContainer()->get('doctrine.orm.entity_manager')->flush();
        }

        return 0;
    }
}
