<?php

use Phpro\HttpTools\Client\FetchConfig;
use Phpro\HttpTools\Transport\Presets\JsonPreset;
use Phpro\HttpTools\Transport\TransportInterface;
use Phpro\HttpTools\Uri\RawUriBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/** @var callable(): TransportInterface<string|null, array> $json */
$json = fn (ClientInterface $client): TransportInterface =>
    JsonPreset::sync($client, RawUriBuilder::createWithAutodiscoveredPsrFactories());
$raw = FetchConfig::defaultTransport();


/**
 * @return FetchConfig<null, empty, empty>
 */
function emptyConfig(): FetchConfig {
    return FetchConfig::of();
}

/**
 * @return FetchConfig<null, string|null, ResponseInterface>
 */
function defaultTransport(): FetchConfig
{
    global $raw;

    return FetchConfig::of(
        transport: $raw,
    );
}

/**
 * @return FetchConfig<string, string|null, ResponseInterface>
 */
function defaultTransportWithData(): FetchConfig
{
    global $raw;

    return FetchConfig::of(
        data: 'hello',
        transport: $raw,
    );
}

/**
 * @return FetchConfig<array, array|null, array>
 */
function mergedWithEmpty(): FetchConfig
{
    global $json;

    return FetchConfig::of()->merge(FetchConfig::of(
        data: [],
        transport: $json,
    ));
}

/**
 * @return FetchConfig<array, array|null, array>
 */
function mergedWithPreviousType(): FetchConfig
{
    global $json, $raw;

    return FetchConfig::of(
        transport: $raw,
    )->merge(FetchConfig::of(
        data: [],
        transport: $json,
    ));
}
