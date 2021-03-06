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
    public function sendEmailMessage(
        $htmlRenderedTemplate,
        $txtRenderedTemplate,
        $subject,
        $toEmail,
        $fromEmail = null,
        $replyEmail = null
    )
    {
        if (!$fromEmail) {
            $fromEmail = 'info@kompisbyran.se';
        }
        if (!$replyEmail) {
            $replyEmail = 'matchning@kompisbyran.se';
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setReplyTo($replyEmail)
            ->setTo($toEmail)
        ;

        if (is_null($htmlRenderedTemplate)) {
            $message->setBody($txtRenderedTemplate, 'text/plain');
        } else if (is_null($txtRenderedTemplate)) {
            $message->setBody($htmlRenderedTemplate, 'text/html');
        } else {
            $message
                ->setBody($htmlRenderedTemplate , 'text/html')
                ->addPart($txtRenderedTemplate  , 'text/plain')
            ;
        }

        $this->mailer->send($message);
    }
}
