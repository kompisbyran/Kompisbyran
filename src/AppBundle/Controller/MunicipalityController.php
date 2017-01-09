<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Municipality;
use AppBundle\Security\Authorization\Voter\MunicipalityVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        ];

        return $this->render('municipality/matched.html.twig', $parameters);
    }
}
