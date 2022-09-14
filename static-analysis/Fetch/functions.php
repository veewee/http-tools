<?php

use Phpro\HttpTools\Client\FetchConfig;
use Phpro\HttpTools\Transport\Presets\JsonPreset;
use Phpro\HttpTools\Transport\TransportInterface;
use Phpro\HttpTools\Uri\RawUriBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use function Phpro\HttpTools\fetch;

$uri = 'https://swapi.dev/api/people';
/** @var callable(): TransportInterface<string|null, array> $json */
$json = fn (ClientInterface $client): TransportInterface =>
    JsonPreset::sync($client, RawUriBuilder::createWithAutodiscoveredPsrFactories());

function urlOnly(): ResponseInterface
{
    global $uri;
    return fetch($uri);
}

function configWithoutDataOrTransport(): ResponseInterface
{
    global $uri;
    return fetch($uri, FetchConfig::of(method: 'OPTIONS'));
}

function configWithTransport(): array
{
    global $uri, $json;
    return fetch($uri, FetchConfig::of(
        transport: $json
    ));
}

function configWithTransportAndMatchingData(): array
{
    global $uri, $json;
    return fetch($uri, FetchConfig::of(
        data: [],
        transport: $json
    ));
}

function configWithTransportAndInvalidData(): array
{
    global $uri, $json;
    return fetch($uri, FetchConfig::of(
        data: 'This is not supported',
        transport: $json
    ));
}
