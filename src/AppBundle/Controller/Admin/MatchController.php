<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Manager\UserManager;
use AppBundle\Manager\ConnectionRequestManager;
use AppBundle\Manager\ConnectionManager;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\User;
use AppBundle\Entity\ConnectionRequest;

/**
 * @Route("admin/matches")
 */
class MatchController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ConnectionManager
     */
    private $connectionManager;

    /**
     * @var ConnectionRequestManager
     */
    private $connectionRequestManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @InjectParams({
     *     "formFactory" = @Inject("form.factory")
     * })
     * @param UserManager $userManager
     * @param ConnectionManager $connectionManager
     * @param ConnectionRequestManager $connectionRequestManager
     */
    public function __construct(UserManager $userManager, ConnectionManager $connectionManager, ConnectionRequestManager $connectionRequestManager, FormFactoryInterface $formFactory)
    {
        $this->userManager              = $userManager;
        $this->connectionManager        = $connectionManager;
        $this->connectionRequestManager = $connectionRequestManager;
        $this->formFactory              = $formFactory;
    }

    /**
     * @Route("/find/{id}", name="admin_match_find", options={"expose"=true})
     * @Method({"GET"})
     */
    public function findAction(Request $request)
    {
        $user = $this->userManager->getFind($request->get('id'));

        if (!$user instanceof User || !$this->connectionRequestManager->getFindOneByUser($user) instanceof ConnectionRequest) {
            throw $this->createNotFoundException();
        }

        $form       = $this->formFactory->create('match_filter', null, [
            'music_friend' => $user->isMusicFriend()
        ]);

        $matchForm  = $this->formFactory->create('match', null, [
            'user' => $user
        ]);

        return $this->render('admin/match/find.html.twig', [
            'user'      => $user,
            'form'      => $form->createView(),
            'matchForm' => $matchForm->createView()
        ]);
    }

    /**
     * @Route("/ajax/results/{id}", name="admin_match_results", options={"expose"=true})
     * @Method({"POST"})
     */
    public function ajaxResultsAction(Request $request, User $user)
    {
        $criterias  = $request->request->all();
        $results    = $this->userManager->getFindMatch($user, $request->get('page', 1), $criterias['match_filter']);

        return new JsonResponse($results);
    }

    /**
     * @Route("/approve", name="admin_match_approve")
     * @Method({"POST"})
     */
    public function approveAction(Request $request)
    {
        $match      = $request->get('match');
        $user       = $this->userManager->getFind($match['user_id']);
        $matchUser  = $this->userManager->getFind($match['match_user_id']);

        if ($user instanceof User && $matchUser instanceof User) {
            if ($this->connectionManager->getIsUserConnectionExists($user, $matchUser)) {

                $this->addFlash('error', sprintf(
                    'Personerna %s och %s har redan kopplats ihop tidigare',
                    $user->getName(),
                    $matchUser->getName()
                ));

            } else {

                $userRequest        = $this->connectionRequestManager->getFindOneByUser($user);
                $matchUserRequest   = $this->connectionRequestManager->getFindOneByUser($matchUser);

                if ($userRequest instanceof ConnectionRequest && $matchUserRequest instanceof ConnectionRequest) {

                    $this->connectionManager->saveByConnectionRequest($userRequest, $matchUserRequest, $this->getUser());
                    $this->connectionRequestManager->remove($userRequest);
                    $this->connectionRequestManager->remove($matchUserRequest);

                    $this->addFlash('info', sprintf(
                        'En koppling skapades melland %s och %s',
                        $userRequest->getUser()->getName(),
                        $matchUserRequest->getUser()->getName()
                    ));
                }
            }

            return $this->redirect($this->generateUrl('admin_start'));
        }

        return $this->createNotFoundException();
    }
}
