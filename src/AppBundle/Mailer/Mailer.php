<?php

namespace AppBundle\Mailer;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class Mailer
 * @package AppBundle\Mailer
 */
class Mailer
{
    /**
     * @var
     */
    protected $mailer;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param EngineInterface $templating
     */
    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, EngineInterface $templating)
    {
        $this->mailer       = $mailer;
        $this->router       = $router;
        $this->templating   = $templating;
    }

    /**
     * @param $htmlRenderedTemplate
     * @param $txtRenderedTemplate
     * @param $subject
     * @param $toEmail
     * @param string $fromEmail
     * @param string $replyEmail
     */
    protected function sendEmailMessage($htmlRenderedTemplate, $txtRenderedTemplate, $subject, $toEmail, $fromEmail = 'info@kompisbyran.se', $replyEmail = 'matchning@kompisbyran.se')
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setReplyTo($replyEmail)
            ->setTo($toEmail)
            ->setBody($htmlRenderedTemplate , 'text/html')
            ->addPart($txtRenderedTemplate  , 'text/plain')
        ;

        $this->mailer->send($message);
    }
}