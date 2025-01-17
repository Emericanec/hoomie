<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\InstagramUserInterface;
use App\Entity\User;
use App\Helper\Env;
use App\Processor\InstagramOAuthProcessor;
use App\Processor\InstagramOAuthResponseProcessor;
use App\Repository\PageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Throwable;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private EntityManagerInterface $em;

    private ?InstagramUserInterface $user = null;

    private PageRepository $pageRepository;

    public function __construct(EntityManagerInterface $em, PageRepository $pageRepository)
    {
        $this->em = $em;
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        return new RedirectResponse('login');
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        $local = 'local' === $request->attributes->get('_route') && Env::isLocalMode();
        $default = 'oauth' === $request->attributes->get('_route') && $request->query->has('code');
        return $default || $local;
    }

    /**
     * @param Request $request
     * @return string|null
     */
    public function getCredentials(Request $request): ?string
    {
        return $request->query->get('code', '');
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return InstagramUserInterface|null
     * @throws Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?InstagramUserInterface
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository(User::class);

        if (Env::isLocalMode()) {
            $this->user = $userRepository->findOneBy([]);
            return $this->user;
        }

        if (null === $credentials) {
            return null;
        }

        try {
            $instagramResponse = (new InstagramOAuthProcessor($credentials))->process();
        } catch (Throwable $e) {
            return null;
        }

        $this->user = $userRepository->findOneBy(['instagramUserId' => $instagramResponse->getUserId()]);

        $processor = new InstagramOAuthResponseProcessor($this->em, $instagramResponse);
        $this->user = null === $this->user ? $processor->createUser($this->pageRepository) : $processor->updateUser($this->user);

        $this->user->setProfileImageUrl($instagramResponse->getProfileImageUrl());

        return $this->user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        return new RedirectResponse('/oauth/error');
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): RedirectResponse
    {
        if (null === $this->user->getEmail()) {
            return new RedirectResponse('/step2');
        }

        return new RedirectResponse('/app/main');
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}