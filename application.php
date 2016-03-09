#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use CornyPhoenix\Component\Redmine\Application;
use CornyPhoenix\Component\Redmine\Normalizer\DateTimeNormalizer;
use CornyPhoenix\Component\Redmine\Normalizer\GetSetMethodNormalizer;
use CornyPhoenix\Component\Redmine\Normalizer\NameConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

$nameConverter = new NameConverter();
$serializer = new \Symfony\Component\Serializer\Serializer([
    new DateTimeNormalizer(),
    new GetSetMethodNormalizer(null, $nameConverter),
], [
    new JsonEncoder(),
]);

// Run the application
$app = new Application($serializer);
$app->run();
