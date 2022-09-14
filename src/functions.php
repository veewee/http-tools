<?php

declare(strict_types=1);

namespace Phpro\HttpTools;

use Phpro\HttpTools\Client\FetchClient;

/**
 * This file contains shortcut functions for quickly running HTTP actions.
 * It is based on axios and fetch from the JS ecosystem.
 * More info.
 *
 * @see \Phpro\HttpTools\Client\FetchClient
 */

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function fetch(string $uri, array $config)
{
    return (new FetchClient())($uri, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function get(string $uri, array $config)
{
    return (new FetchClient())->get($uri, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function options(string $uri, array $config)
{
    return (new FetchClient())->options($uri, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function head(string $uri, array $config)
{
    return (new FetchClient())->head($uri, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function delete(string $uri, array $config)
{
    return (new FetchClient())->delete($uri, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param RequestData $data
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function post(string $uri, mixed $data, array $config)
{
    return (new FetchClient())->post($uri, $data, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param RequestData $data
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function put(string $uri, mixed $data, array $config)
{
    return (new FetchClient())->put($uri, $data, $config);
}

/**
 * @template RequestData
 * @template ResponseData
 * @psalm-import-type PartialConfig from FetchClient
 *
 * @param RequestData $data
 * @param PartialConfig $config
 *
 * @return ResponseData
 */
function patch(string $uri, mixed $data, array $config)
{
    return (new FetchClient())->patch($uri, $data, $config);
}
