<?php

namespace AppBundle\Command;

use AppBundle\Entity\InstagramImage;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstagramCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('kompisbyran:instagram')
            ->setDescription('Fetch Instagram images')
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
        $content = file_get_contents(
            sprintf(
                'https://api.instagram.com/v1/users/%s/media/recent/?access_token=%s',
                $this->getContainer()->getParameter('instagram.userid'),
                $this->getContainer()->getParameter('instagram.access_token')
            )
        );
        $data = json_decode($content);

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        if ($data->data) {
            foreach ($data->data as $post) {
                if ('image' != $post->type) {
                    continue;
                }
                $output->writeln(sprintf('Handling "<comment>%s</comment>"', $post->caption->text));

                /** @var InstagramImage|null $instagramImage */
                $instagramImage = $em->getRepository(InstagramImage::class)->findOneByInstagramImageId($post->id);
                if (!$instagramImage) {
                    $instagramImage = new InstagramImage(
                        $post->id,
                        \DateTime::createFromFormat('U', $post->created_time),
                        $post->images->standard_resolution->url,
                        $post->link,
                        $post->user->username,
                        $post->user->profile_picture,
                        $post->caption->text,
                        $post->location ? $post->location->name : null,
                        $post->likes->count
                    );
                } else {
                    $instagramImage->setLikesCount($post->likes->count);
                    $instagramImage->setCaption($post->caption->text);
                    $instagramImage->setLocation($post->location ? $post->location->name : null);
                }
                $em->persist($instagramImage);
                $em->flush();
            }
        }

        return 0;
    }
}
