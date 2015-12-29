<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ConnectionComment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("admin/connectioncomments")
 */
class ConnectionCommentController extends Controller
{
    /**
     * @Route("/", name="admin_add_connection_comment")
     * @Method("POST")
     */
    public function addAction(Request $request)
    {
        $connection = $this->getConnectionRepository()->find($request->request->getInt('connectionId'));
        if (!$connection) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }
        $connectionComment = new ConnectionComment();
        $connectionComment->setComment($request->request->get('comment'));
        $connectionComment->setConnection($connection);
        $connectionComment->setUser($this->getUser());
        $this->getDoctrine()->getEntityManager()->persist($connectionComment);
        $this->getDoctrine()->getEntityManager()->flush();

        return new Response();
    }

    protected function getConnectionRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Connection');
    }
}
