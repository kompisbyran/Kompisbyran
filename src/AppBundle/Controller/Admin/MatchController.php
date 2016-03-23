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
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Entity\User;

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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @InjectParams({
     *     "formFactory" = @Inject("form.factory")
     * })
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager, FormFactoryInterface $formFactory)
    {
        $this->userManager = $userManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route("/find/{id}", name="admin_match_find", options={"expose"=true})
     * @Method({"GET"})
     */
    public function findAction(Request $request)
    {
        $user   = $this->userManager->getFind($request->get('id'));
        $form   = $this->formFactory->create('match_filter', null, [
            'music_friend' => $user->isMusicFriend()
        ]);

        return $this->render('admin/match/find.html.twig', [
            'user'  => $user,
            'form'  => $form->createView()
        ]);
    }

    /**
     * @Route("/results/{id}", name="admin_match_results", options={"expose"=true})
     * @Method({"POST"})
     */
    public function resultsAction(Request $request, User $user)
    {
        $criterias  = $request->request->all();
        $results    = $this->userManager->getFindMatch($user, $request->get('page', 1), $criterias['match_filter']);

        return new JsonResponse($results);
    }
}
