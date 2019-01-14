<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteUsersCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:delete-users')
            ->setDescription('Delete inactive users')
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
        $date->modify(sprintf('-%s days', $this->getContainer()->getParameter('days_to_accept_keep_inactive_user_before_deletion')));

        $output->writeln(sprintf('Deleting users inactive since %s.', $date->format('Y-m-d')));

        $users = $this->getContainer()->get('user_manager')->deleteInactiveSince($date);
        $output->writeln(sprintf('Deleted %s users', count($users)));

        return 0;
    }
}
