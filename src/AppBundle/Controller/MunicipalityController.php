<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\Municipality;
use AppBundle\Entity\User;
use AppBundle\Security\Authorization\Voter\MunicipalityVoter;
use AppBundle\Security\Authorization\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MunicipalityController extends Controller
{
    /**
     * @Route("/municipalities/{id}/waiting", name="municipality_waiting", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function waitingAction(Municipality $municipality)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);

        $connectionRequests = $this->get('connection_request_repository')->findInspectedStartFriendsByMunicipality(
            $municipality
        );

        $parameters = [
            'connectionRequests' => $connectionRequests,
            'municipality' => $municipality,
        ];

        return $this->render('municipality/waiting.html.twig', $parameters);
    }

    /**
     * @Route(
     *     "/municipalities/{id}/matched",
     *     name="municipality_matched",
     *     requirements={"id": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("GET")
     */
    public function matchedAction(Municipality $municipality)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);

        $connections = $this->get('connection_repository')->findStartFriendsByMunicipality($municipality);

        $parameters = [
            'connections' => $connections,
            'municipality' => $municipality,
        ];

        return $this->render('municipality/matched.html.twig', $parameters);
    }

    /**
     * @Route(
     *     "/municipalities/{municipalityId}/users/{userId}",
     *     name="municipality_user_fragment",
     *     requirements={"municipalityId": "\d+", "userId": "\d+"},
     *     options={"expose"=true}
     * )
     * @ParamConverter(
     *     "municipality",
     *     class="AppBundle:Municipality",
     *     options={"id"="municipalityId"}
     * )
     * @ParamConverter(
     *     "user",
     *     class="AppBundle:User",
     *     options={"id"="userId"}
     * )
     * @Method("GET")
     */
    public function userFragmentAction(Municipality $municipality, User $user)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);
        $this->denyAccessUnlessGranted(UserVoter::VIEW, $user);

        $parameters = [
            'user' => $user,
        ];

        return $this->render('municipality/userfragment.html.twig', $parameters);
    }
}
