<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\Connection;
use AppBundle\Entity\User;
use AppBundle\Enum\FriendTypes;
use AppBundle\Event\FollowUpEmailSentEvent;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class UserMailer
 * @package AppBundle\Mailer
 */
class UserMailer extends Mailer
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        \Swift_Mailer $mailer,
        RouterInterface $router,
        EngineInterface $templating,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;

        parent::__construct($mailer, $router, $templating);
    }

    /**
     * @param User $user
     */
    public function sendRegistrationWelcomeEmailMessage(User $user)
    {
        $subject = sprintf('email.welcome.%s.subject', $user->getType());
        $htmlBody = sprintf('email.welcome.%s.body', $user->getType());

        $subject = $this->translator->trans($subject);
        $htmlBody = $this->translator->trans(
            $htmlBody,
            [
                '%firstName%' => $user->getFirstName(),
            ]
        );

        $html = $this->templating->render('email/welcome.html.twig', [
            'body' => $htmlBody
        ]);

        $replyEmail = null;
        if ($user->getType() == FriendTypes::START) {
            $replyEmail = 'start@kompisbyran.se';
        }

        $this->sendEmailMessage($html, null, $subject, $user->getEmail(), null, $replyEmail);
    }

    /**
     * @param User $user
     * @param User $matchUser
     * @param $body
     * @param $fromEmail
     */
    public function sendMatchEmailMessage(User $user, User $matchUser, $body, $fromEmail)
    {
        $typeText = $matchUser->getType() == FriendTypes::MUSIC
            ? $this->translator->trans('global.music_buddy')
            : $this->translator->trans('global.fika_buddy');

        $subject = sprintf('%s, här är din %s från Kompisbyrån', $user->getFullName(), $typeText);

        $this->sendEmailMessage(null, $body, $subject, $user->getEmail(), $fromEmail, $fromEmail);
    }

    /**
     * @param User $user
     * @param Connection $connection
     */
    public function sendConfirmMeetingMessage(User $user, Connection $connection)
    {
        $subject = 'Fråga från Kompisbyrån';

        $friend = $connection->getFluentSpeaker();
        if ($friend == $user) {
            $friend = $connection->getLearner();
        }

        $html = $this->templating->render('email/confirmMeeting.html.twig', [
            'user' => $user,
            'connection' => $connection,
            'friend' => $friend,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());

        $this->eventDispatcher->dispatch(
            FollowUpEmailSentEvent::MEETING_CONFIRMATION_EMAIL_SENT,
            new FollowUpEmailSentEvent($user, $connection)
        );
    }

    /**
     * @param User $user
     * @param Connection $connection
     */
    public function sendFollowUpEmail2Message(User $user, Connection $connection)
    {
        $template = 'followUp';
        $subject = 'Fråga från Kompisbyrån';


        if ($connection->getType() == FriendTypes::START) {
            if ($connection->getFluentSpeaker() == $user) {
                $template = 'followUpStartFriendFluentSpeaker';
                $subject = 'Vi efterfrågar din upplevelse av att träffa en startkompis';
            } else {
                $template = 'followUpStartFriendLearner';
                $subject = 'Fråga från Kompisbyrån / Request from Kompisbyrån';
            }
        }

        $html = $this->templating->render(sprintf('email/%s.html.twig', $template), [
            'user' => $user,
            'connection' => $connection,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());

        $this->eventDispatcher->dispatch(
            FollowUpEmailSentEvent::FOLLOW_UP_EMAIL2_SENT,
            new FollowUpEmailSentEvent($user, $connection)
        );
    }
}
