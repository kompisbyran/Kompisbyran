<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Municipality;
use AppBundle\Entity\PreMatch;
use AppBundle\Entity\PreMatchIgnore;
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
     * @Route("/pre-matches/{id}", name="pre_matches", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function indexAction(Municipality $municipality)
    {
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_VIEW, $municipality);

        $municipalities = $this->getUser()->getAdminMunicipalities();

        if (count($municipality->getPreMatches()) == 0) {
            $this->get('manager.pre_match')->createMatches($municipality);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
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

        $this->getDoctrine()->getManager()->refresh($preMatch);

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

        return new Response('', Response::HTTP_NO_CONTENT);
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
        $this->denyAccessUnlessGranted(MunicipalityVoter::ADMIN_DELETE, $preMatch->getMunicipality());

        $this->getDoctrine()->getManager()->remove($preMatch);
        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
