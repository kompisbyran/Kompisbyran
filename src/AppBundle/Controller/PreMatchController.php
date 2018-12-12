<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\Municipality;
use AppBundle\Entity\PreMatch;
use AppBundle\Entity\PreMatchIgnore;
use AppBundle\Enum\FriendTypes;
use AppBundle\Security\Authorization\Voter\MunicipalityVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PreMatchController extends Controller
{
    /**
     * @Route(
     *     "/pre-matches/{id}",
     *     name="pre_matches",
     *     requirements={"id": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("GET")
     */
    public function indexAction(Municipality $municipality)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);

        $municipalities = $this->getUser()->getAdminMunicipalities();

        $this->get('manager.pre_match')->createMatches($municipality);

        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MUNICIPALITY_ADMIN')) {
            $this->getDoctrine()->getManager()->refresh($municipality);
            $preMatches = $municipality->getPreMatches();
        } else {
            $preMatches = $this->get('pre_match_repository')->findVerifiedByMunicipality($municipality);
        }

        $parameters = [
            'municipalities' => $municipalities,
            'municipality' => $municipality,
            'preMatches' => $preMatches,
        ];

        return $this->render('preMatch/index.html.twig', $parameters);
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}/fragment",
     *     name="pre_match_fragment",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("GET")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     */
    public function fragmentAction(PreMatch $preMatch)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $preMatch->getMunicipality());
        $parameters = [
            'preMatch' => $preMatch,
        ];

        return $this->render('preMatch/fragment.html.twig', $parameters);
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}",
     *     name="re_pre_match",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("PUT")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     */
    public function rePreMatchAction(PreMatch $preMatch)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $preMatch->getMunicipality());

        if ($preMatch->getFluentSpeakerConnectionRequest()) {
            $preMatchIgnore = new PreMatchIgnore();
            $preMatchIgnore->setFluentSpeaker($preMatch->getFluentSpeakerConnectionRequest()->getUser());
            $preMatchIgnore->setLearner($preMatch->getLearnerConnectionRequest()->getUser());
            $preMatch->addPreMatchIgnore($preMatchIgnore);
            $preMatch->setFluentSpeakerConnectionRequest(null);
            $this->getDoctrine()->getManager()->persist($preMatch);
            $this->getDoctrine()->getManager()->flush();
        } else {
            foreach ($preMatch->getPreMatchIgnores() as $preMatchIgnore) {
                $this->getDoctrine()->getManager()->remove($preMatchIgnore);
            }
            $this->getDoctrine()->getManager()->flush();
        }

        $this->get('manager.pre_match')->createMatchForConnectionRequest(
            $preMatch->getLearnerConnectionRequest(), $preMatch
        );
        $this->getDoctrine()->getManager()->persist($preMatch);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($this->get('serializer')->normalize($preMatch));
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}",
     *     name="patch_pre_match",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("PATCH")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     */
    public function patchAction(PreMatch $preMatch, Request $request)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $preMatch->getMunicipality());

        if ($request->request->has('verified')) {
            $preMatch->setVerified($request->request->getBoolean('verified'));
            $this->getDoctrine()->getManager()->persist($preMatch);
            $this->getDoctrine()->getManager()->flush();
        }
        if ($request->request->has('confirm') && $request->request->getBoolean('confirm')) {
            $connection = new Connection();
            $connection->setType(FriendTypes::START);
            $connection->setCreatedBy($this->getUser());
            $connection->setFluentSpeaker($preMatch->getFluentSpeakerConnectionRequest()->getUser());
            $connection->setFluentSpeakerConnectionRequestCreatedAt($preMatch->getFluentSpeakerConnectionRequest()->getCreatedAt());
            $connection->setLearner($preMatch->getLearnerConnectionRequest()->getUser());
            $connection->setLearnerConnectionRequestCreatedAt($preMatch->getLearnerConnectionRequest()->getCreatedAt());
            $connection->setMunicipality($preMatch->getMunicipality());
            $connection->setFluentSpeakerConnectionRequest($preMatch->getFluentSpeakerConnectionRequest());
            $connection->setLearnerConnectionRequest($preMatch->getLearnerConnectionRequest());
            $this->getDoctrine()->getManager()->persist($connection);
            $this->getDoctrine()->getManager()->remove($preMatch);
            $this->getDoctrine()->getManager()->flush();

            $this->get('app.mailer')->sendEmailMessage(
                null,
                $request->request->get('fluentSpeakerEmail'),
                sprintf('%s, här är din matchning från Kompisbyrån', $preMatch->getFluentSpeakerConnectionRequest()->getUser()->getFirstName()),
                $preMatch->getFluentSpeakerConnectionRequest()->getUser()->getEmail()
            );

            $this->get('app.mailer')->sendEmailMessage(
                null,
                $request->request->get('learnerEmail'),
                sprintf('%s, här är din matchning från Kompisbyrån', $preMatch->getLearnerConnectionRequest()->getUser()->getFirstName()),
                $preMatch->getLearnerConnectionRequest()->getUser()->getEmail()
            );
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}/confirm",
     *     name="pre_match_confirm",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+"}
     * )
     * @Method("GET")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     */
    public function confirmAction(PreMatch $preMatch)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $preMatch->getMunicipality());
        $parameters = [
            'preMatch' => $preMatch,
        ];

        return $this->render('preMatch/confirm.html.twig', $parameters);
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}/{connectionRequestId}",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+", "connectionRequestId": "\d+"}
     * )
     * @Method("GET")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     * @ParamConverter(
     *     "connectionRequest",
     *     class="AppBundle:ConnectionRequest",
     *     options={"id"="connectionRequestId"}
     * )
     */
    public function renderEmailAction(PreMatch $preMatch, ConnectionRequest $connectionRequest)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $preMatch->getMunicipality());
        if ($preMatch->getLearnerConnectionRequest() != $connectionRequest
            && $preMatch->getFluentSpeakerConnectionRequest() != $connectionRequest)
        {
            throw $this->createNotFoundException();
        }

        if ($preMatch->getFluentSpeakerConnectionRequest() == $connectionRequest) {
            $otherUser = $preMatch->getLearnerConnectionRequest()->getUser();
            $otherUserConnectionRequest = $preMatch->getLearnerConnectionRequest();
        } else {
            $otherUser = $preMatch->getFluentSpeakerConnectionRequest()->getUser();
            $otherUserConnectionRequest = $preMatch->getFluentSpeakerConnectionRequest();
        }

        $parameters = [
            'connectionRequest' => $connectionRequest,
            'user' => $connectionRequest->getUser(),
            'otherUser' => $otherUser,
            'otherUserConnectionRequest' => $otherUserConnectionRequest,
            'preMatch' => $preMatch,
        ];

        return $this->render('preMatch/email.txt.twig', $parameters);
    }

    /**
     * @Route(
     *     "/pre-matches/{municipalityId}/{preMatchId}",
     *     name="delete_pre_match",
     *     requirements={"municipalityId": "\d+", "preMatchId": "\d+"},
     *     options={"expose"=true}
     * )
     * @Method("DELETE")
     * @ParamConverter(
     *     "preMatch",
     *     class="AppBundle:PreMatch",
     *     options={
     *         "repository_method"="findByMunicipalityIdAndPreMatchId",
     *         "map_method_signature"=true
     *     }
     * )
     */
    public function deletePreMatchAction(PreMatch $preMatch)
    {
         $this->denyAccessUnlessGranted('ROLE_ADMIN');

         $this->getDoctrine()->getManager()->remove($preMatch);
         $this->getDoctrine()->getManager()->flush();

         return new Response('', Response::HTTP_NO_CONTENT);
    }
}
