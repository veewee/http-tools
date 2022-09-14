<?php

use Phpro\HttpTools\Client\FetchClient;
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
$raw = FetchConfig::defaultTransport();

function urlOnly(): ResponseInterface
{
    global $uri;
    return (new FetchClient())($uri);
}

function configWithoutDataOrTransport(): ResponseInterface
{
    global $uri;
    return (new FetchClient())($uri, FetchConfig::of(method: 'OPTIONS'));
}

function configWithTransport(): array
{
    global $uri, $json;
    return (new FetchClient())($uri, FetchConfig::of(
        transport: $json,
    ));
}

function configWithTransportAndMatchingData(): array
{
    global $uri, $json;
    return (new FetchClient())($uri, FetchConfig::of(
        data: [],
        transport: $json,
    ));
}

function configWithTransportAndInvalidData(): array
{
    global $uri, $json;
    return (new FetchClient())($uri, FetchConfig::of(
        data: 'This is not supported',
        transport: $json,
    ));
}

function instanceTransport(): array
{
    global $uri, $json;
    return (new FetchClient(
        FetchConfig::of(
            transport: $json,
        )
    ))($uri, FetchConfig::of(
        data: [],
    ));
}

function instanceAndCallTimeTransport(): array
{
    global $uri, $raw, $json;
    return (new FetchClient(
        FetchConfig::of(
            transport: $raw,
        )
    ))($uri, FetchConfig::of(
        data: [],
        transport: $json,
    ));
}
