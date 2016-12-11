<?php

namespace AppBundle\Controller\Admin2;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\Municipality;
use AppBundle\Entity\MunicipalityRepository;
use AppBundle\Entity\User;
use AppBundle\Form\AdminUserType;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Manager\UserManager;
use Symfony\Component\Form\FormFactoryInterface;
use AppBundle\Manager\ConnectionRequestManager;
use AppBundle\Manager\CityManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("admin2/users")
 */
class UserController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ConnectionRequestManager
     */
    private $connectionRequestManager;

    /**
     * @var CityManager
     */
    private $cityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var MunicipalityRepository
     */
    private $municipalityRepository;

    /**
     * @InjectParams({
     *     "formFactory" = @Inject("form.factory")
     * })
     * @param UserManager $userManager
     * @param ConnectionRequestManager $connectionRequestManager
     * @param CityManager $cityManager
     */
    public function __construct(
        UserManager $userManager,
        ConnectionRequestManager $connectionRequestManager,
        CityManager $cityManager,
        FormFactoryInterface $formFactory,
        MunicipalityRepository $municipalityRepository
    )
    {
        $this->userManager              = $userManager;
        $this->connectionRequestManager = $connectionRequestManager;
        $this->cityManager              = $cityManager;
        $this->formFactory              = $formFactory;
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * @Route("/ajax/edit/{id}", name="admin_ajax_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function ajaxEditAction(Request $request, User $user)
    {
        $userRequest    = $this->connectionRequestManager->getFindOneByUserId($user->getId());
        $form           = $this->formFactory->create('admin_user', $user, [
            'manager'   => $this->getDoctrine()->getManager(),
            'locale'    => $request->getLocale()
        ]);

        $requestForm    = $this->formFactory->create('connectionRequest', $userRequest);

        $form->handleRequest($request);

        if ($request->isMethod(Request::METHOD_POST)) {
            if ($form->isValid()) {
                $this->userManager->save($user);

                return new JsonResponse(['success' => true]);
            } else {
                return new JsonResponse(['success' => false]);
            }
        }

        return $this->render('admin2/user/form.html.twig', [
            'form'          => $form->createView(),
            'requestForm'   => $requestForm->createView(),
            'user'          => $user,
            'request_id'    => $userRequest->getId()
        ]);
    }

    /**
     * @Route("/priviledges", name="admin_user_priviledges")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @Method("GET")
     */
    public function priviledgeAction()
    {
        $administrators = $this->userManager->getFindAllAdmin();
        $municipalityAdministrators = array_merge($this->userManager->getAllMunicipalityAdministrators(), $administrators);

        $parameters = [
            'administrators' => $administrators,
            'municipalityAdministrators' => $municipalityAdministrators,
            'cities' => $this->cityManager->getFindAll(),
            'municipalities' => $this->municipalityRepository->findAll(),
        ];

        return $this->render('admin2/user/priviledge.html.twig', $parameters);
    }

    /**
     * @Route("/ajax/add-city/{id}", name="admin_user_add_city", options={"expose"=true})
     * @Method({"POST"})
     */
    public function ajaxAddCityAction(Request $request, User $user)
    {
        $city   = $this->cityManager->getFind($request->get('city_id'));
        $result = $this->userManager->addUserCity($user, $city);

        return new JsonResponse([
            'success' => $result
        ]);
    }

    /**
     * @Route("/ajax/remove-city/{id}", name="admin_user_remove_city", options={"expose"=true})
     * @Method({"POST"})
     */
    public function ajaxRemoveCityAction(Request $request, User $user)
    {
        $city   = $this->cityManager->getFind($request->get('city_id'));
        $result = $this->userManager->removeUserCity($user, $city);

        return new JsonResponse([
            'success' => $result
        ]);
    }

    /**
     * @Route(
     *     "/{id}/municipalities",
     *     name="admin_user_add_municipality",
     *     options={"expose"=true},
     *     requirements={"id": "\d+"}
     * )
     * @Method("POST")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function addMunicipalityAction(User $user, Request $request)
    {
        $municipality = $this->municipalityRepository->find($request->get('municipalityId'));
        $user->addAdminMunicipality($municipality);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(
     *     "/{userId}/municipalities/{municipalityId}",
     *     name="admin_user_remove_municipality",
     *     options={"expose"=true},
     *     requirements={"userId": "\d+", "municipalityId": "\d+"},
     * )
     * @ParamConverter(
     *     "user",
     *     class="AppBundle:User",
     *     options={"id"="userId"}
     * )
     * @ParamConverter(
     *     "municipality",
     *     class="AppBundle:Municipality",
     *     options={"id"="municipalityId"}
     * )
     * @Method("DELETE")
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     */
    public function removeMunicipalityAction(User $user, Municipality $municipality)
    {
        $user->removeAdminMunicipality($municipality);
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
