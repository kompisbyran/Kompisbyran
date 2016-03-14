<?php

namespace AppBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UserMailer
 * @package AppBundle\Mailer
 */
class UserMailer extends Mailer
{
    protected $translator;

    /**
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, EngineInterface $templating, TranslatorInterface $translator)
    {
        $this->translator = $translator;

        parent::__construct($mailer, $router, $templating);
    }

    /**
     * @param UserInterface $user
     */
    public function sendRegistrationWelcomeEmailMessage(UserInterface $user)
    {
        $subject    = 'user.welcome.message.subject';
        $htmlBody   = 'user.welcome.message.body';
        $txtBody    = 'user.welcome.message.body.txt';

        if ($user->isMusicFriend()) {
            $subject    = 'user.friend.welcome.message.subject';
            $htmlBody   = 'user.friend.welcome.message.body';
            $txtBody    = 'user.friend.welcome.message.body.txt';
        }

        $subject    = $this->translator->trans($subject);
        $htmlBody   = $this->translator->trans($htmlBody);
        $txtBody    = $this->translator->trans($txtBody);

        $html       = $this->templating->render('email/welcome.html.twig', [
            'body'  =>  $htmlBody
        ]);

        $txt        = $this->templating->render('email/welcome.txt.twig', [
            'body'  => $txtBody
        ]);

        //$this->sendEmailMessage($html, $txt, $subject, $user->getEmail());
        $this->sendEmailMessage($html, $txt, $subject, 'angela14201482@gmail.com');
    }
}