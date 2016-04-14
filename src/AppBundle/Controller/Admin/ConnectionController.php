<?php

namespace AppBundle\Controller\Admin;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
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
        $searchString   = $request->query->get('q');
        $queryBuilder   = $this->getConnectionRepository()->getFindAllQueryBuilder($searchString);
        $adapter        = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta     = new Pagerfanta($adapter);

        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($request->query->getInt('page', 1));

        $parameters = [
            'pagerfanta' => $pagerfanta
        ];

        return $this->render('admin/connection/index.html.twig', $parameters);
    }

    protected function getConnectionRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Connection');
    }
}
