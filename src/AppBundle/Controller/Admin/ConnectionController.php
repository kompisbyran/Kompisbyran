<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("admin/connections")
 */
class ConnectionController extends Controller
{
    /**
     * @Route("/", name="admin_connections")
     */
    public function indexAction(Request $request)
    {
        $searchString = $request->query->get('q');

        $query = $this->getConnectionRepository()->getFindAllQuery($searchString);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $parameters = [
            'pagination' => $pagination,
        ];

        return $this->render('admin/connection/index.html.twig', $parameters);
    }

    protected function getConnectionRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Connection');
    }
}
