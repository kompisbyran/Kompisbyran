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
     * @var int
     */
    protected $daysToAcceptKeepInactiveUserBeforeDeletion;

    /**
     * @param \Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param EngineInterface $templating
     * @param TranslatorInterface $translator
     * @param EventDispatcherInterface $eventDispatcher
     * @param int $daysToAcceptKeepInactiveUserBeforeDeletion
     */
    public function __construct(
        \Swift_Mailer $mailer,
        RouterInterface $router,
        EngineInterface $templating,
        TranslatorInterface $translator,
        EventDispatcherInterface $eventDispatcher,
        $daysToAcceptKeepInactiveUserBeforeDeletion
    )
    {
        $this->translator = $translator;
        $this->eventDispatcher = $eventDispatcher;
        $this->daysToAcceptKeepInactiveUserBeforeDeletion = $daysToAcceptKeepInactiveUserBeforeDeletion;

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
     */
    public function sendIncompleteUserEmailMessage(User $user)
    {
        $subject = 'Gör färdigt din anmälan till Kompisyrån';

        $html = $this->templating->render('email/incomplete.html.twig', [
            'user' => $user,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());
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

        $fromEmail = null;
        if ($connection->getMunicipality()) {
            if ($connection->getMunicipality()->getFollowUpEmailTemplate()) {
                $template = 'followUp/' . $connection->getMunicipality()->getFollowUpEmailTemplate();
            }
            if ($connection->getMunicipality()->getSenderEmail()) {
                $fromEmail = $connection->getMunicipality()->getSenderEmail();
            }
        }
        $html = $this->templating->render(sprintf('email/%s.html.twig', $template), [
            'user' => $user,
            'connection' => $connection,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail(), $fromEmail);

        $this->eventDispatcher->dispatch(
            FollowUpEmailSentEvent::FOLLOW_UP_EMAIL2_SENT,
            new FollowUpEmailSentEvent($user, $connection)
        );
    }

    /**
     * @param Connection $connection
     */
    public function sendMeetAgainMessage(Connection $connection)
    {
        if (!$connection->getFluentSpeakerConnectionRequest()) {
            return;
        }

        $user = $connection->getFluentSpeaker();
        $subject = 'Vill du träffa en ny kompis?';

        $html = $this->templating->render('email/meetAgain.html.twig', [
            'user' => $user,
            'connection' => $connection,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());
    }
  
    /**
     * @param User $user
     * @param Connection $connection
     */
    public function sendFollowUpEmail3Message(User $user, Connection $connection)
    {
        $subject = 'Fråga från Kompisbyrån';

        $friend = $connection->getFluentSpeaker();
        if ($friend == $user) {
            $friend = $connection->getLearner();
        }

        $html = $this->templating->render('email/followUp3.html.twig', [
            'user' => $user,
            'friend' => $friend,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());
    }

    /**
     * @param User $user
     */
    public function sendUserIsAboutToBeDeletedMessage(User $user)
    {
        $deleteDate = new \DateTime();
        $deleteDate->modify(sprintf('+%s days', $this->daysToAcceptKeepInactiveUserBeforeDeletion));

        $subject = 'Vill du fortsätta träffa nya vänner med Kompisbyrån?';

        $html = $this->templating->render('email/userIsAboutToBeDeleted.html.twig', [
            'user' => $user,
            'deleteDate' => $deleteDate,
        ]);

        $this->sendEmailMessage($html, null, $subject, $user->getEmail());
    }
}
