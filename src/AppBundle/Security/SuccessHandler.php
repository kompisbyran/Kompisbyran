<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class SuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        foreach ($token->getRoles() as $role) {
            if ('ROLE_ADMIN' == $role->getRole()) {
                return new RedirectResponse($this->router->generate('admin_start'));
            }

            if ('ROLE_MUNICIPALITY' == $role->getRole()) {
                /** @var User $user */
                $user = $token->getUser();
                return new RedirectResponse(
                    $this->router->generate('pre_matches', ['id' => $user->getAdminMunicipalities()[0]->getId()])
                );
            }

            if ('ROLE_MUNICIPALITY_ADMIN' == $role->getRole()) {
                /** @var User $user */
                $user = $token->getUser();
                if ($user->getAdminMunicipalities()) {
                    return new RedirectResponse(
                        $this->router->generate('municipality_waiting', ['id' => $user->getAdminMunicipalities()[0]->getId()])
                    );
                }
            }
        }

        return new RedirectResponse($this->router->generate('homepage'));
    }
}
