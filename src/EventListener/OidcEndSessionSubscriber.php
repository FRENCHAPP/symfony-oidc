<?php

namespace Faw\OidcBundle\EventListener;

use Faw\OidcBundle\Exception\OidcConfigurationException;
use Faw\OidcBundle\Exception\OidcConfigurationResolveException;
use Faw\OidcBundle\Exception\OidcException;
use Faw\OidcBundle\OidcClientInterface;
use Faw\OidcBundle\Security\Token\OidcToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\HttpUtils;

class OidcEndSessionSubscriber implements EventSubscriberInterface
{
  public function __construct(
    private readonly OidcClientInterface $oidcClient,
    private readonly HttpUtils $httpUtils,
    private readonly ?string $logoutTarget = null)
  {
  }

  /**
   * @throws OidcConfigurationException
   * @throws OidcConfigurationResolveException
   * @throws OidcException
   */
  public function onLogout(LogoutEvent $event): void
  {
    $token = $event->getToken();

    if (!$token instanceof OidcToken) {
      return;
    }

    $postLogoutRedirectUrl = null !== $this->logoutTarget
        ? $this->httpUtils->generateUri($event->getRequest(), $this->logoutTarget)
        : null;

    $event->setResponse($this->oidcClient->generateEndSessionEndpointRedirect(
      $token->getAuthData(),
      $postLogoutRedirectUrl
    ));
  }

  public static function getSubscribedEvents(): array
  {
    return [LogoutEvent::class => 'onLogout'];
  }
}
