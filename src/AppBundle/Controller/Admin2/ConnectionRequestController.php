<?php

namespace AppBundle\Controller\Admin2;

use AppBundle\Manager\ConnectionRequestManager;
use AppBundle\Manager\CityManager;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use AppBundle\Entity\City;
use AppBundle\Form\EditConnectionRequestType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("admin2/connectionrequests")
 */
class ConnectionRequestController extends Controller
{
    /**
     * @var ConnectionRequestManager
     */
    private $connectionRequestManager;

    /**
     * @var CityManager
     */
    private $cityManager;

    /**
     * @InjectParams({
     *     "connectionRequestManager"   = @Inject("connection_request_manager"),
     *     "cityManager"                = @Inject("city_manager")
     *
     * })
     */
    public function __construct(ConnectionRequestManager $connectionRequestManager, CityManager $cityManager)
    {
        $this->connectionRequestManager = $connectionRequestManager;
        $this->cityManager              = $cityManager;
    }

    /**
     * @Route("/ajax-by-city/{id}", name="ajax_by_city", options={"expose"=true})
     * @Method({"GET"})
     */
    public function ajaxByCityAction(Request $request)
    {
        $city   = $this->cityManager->getFind($request->get('id'));

        if ($city instanceof City) {
            $results = $this->connectionRequestManager->getFindPaginatedByCityResults($city, $request->get('page', 1));
        } else {
            $results = [
                'success'   => false,
                'message'   => 'City not found!'
            ];
        }

        return new JsonResponse($results);
    }

    /**
     * @Route("/ajax-mark-pending/{id}", name="admin_ajax_connection_request_mark_pending", options={"expose"=true})
     * @Method({"GET"})
     */
    public function ajaxMarkPendingAction(Request $request)
    {
        return new JsonResponse([
            'success' => $this->connectionRequestManager->markAsPending($request->get('id'))
        ]);
    }

    /**
     * @Route("/ajax-mark-inspected/{id}", name="admin_ajax_connection_request_mark_inspected", options={"expose"=true})
     * @Method({"GET"})
     */
    public function ajaxMarkInspectedAction(Request $request)
    {
        return new JsonResponse([
            'success' => $this->connectionRequestManager->markAsInspected($request->get('id'))
        ]);
    }

    /**
     * @Route("/mark-unpending/{id}", name="admin_connection_request_mark_unpending", options={"expose"=true})
     * @Method({"GET"})
     */
    public function markUnpendingAction(Request $request)
    {
        $this->connectionRequestManager->markAsPendingOrUnpending($request->get('id'));

        return $this->redirect($this->generateUrl('admin_manual'));
    }
}
