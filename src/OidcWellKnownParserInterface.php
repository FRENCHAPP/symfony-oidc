<?php

namespace Faw\OidcBundle;

interface OidcWellKnownParserInterface
{
  public function parseWellKnown(array $config): array;
}
