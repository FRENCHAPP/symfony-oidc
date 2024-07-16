<?php

use Faw\OidcBundle\DependencyInjection\FawOidcExtension;
use Faw\OidcBundle\OidcClient;
use Faw\OidcBundle\OidcClientLocator;
use Faw\OidcBundle\OidcJwtHelper;
use Faw\OidcBundle\OidcSessionStorage;
use Faw\OidcBundle\OidcUrlFetcher;
use Faw\OidcBundle\Security\OidcAuthenticator;
use Psr\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Contracts\Cache\CacheInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
  $configurator->services()
    ->set(FawOidcExtension::AUTHENTICATOR_ID, OidcAuthenticator::class)
    ->abstract()

    ->set(FawOidcExtension::URL_FETCHER_ID, OidcUrlFetcher::class)
    ->abstract()

    ->set(FawOidcExtension::SESSION_STORAGE_ID, OidcSessionStorage::class)
    ->args([
      service(RequestStack::class),
    ])
    ->abstract()

    ->set(FawOidcExtension::JWT_HELPER_ID, OidcJwtHelper::class)
    ->args([
      service(CacheInterface::class)->nullOnInvalid(),
      service(ClockInterface::class)->nullOnInvalid(),
    ])
    ->abstract()

    ->set(FawOidcExtension::CLIENT_ID, OidcClient::class)
    ->args([
      service(RequestStack::class),
      service(HttpUtils::class),
      service(CacheInterface::class)->nullOnInvalid(),
    ])
    ->abstract()

    ->set(FawOidcExtension::CLIENT_LOCATOR_ID, OidcClientLocator::class)
    ->alias(OidcClientLocator::class, FawOidcExtension::CLIENT_LOCATOR_ID)
  ;
};
