<?php

declare(strict_types=1);

namespace Phpro\HttpTools;

use Phpro\HttpTools\Client\FetchClient;
use Phpro\HttpTools\Client\FetchConfig;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-suppress UnusedVariable
 *
 * @see \Phpro\HttpTools\Client\FetchClient
 */

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @ psalm-suppress MixedInferredReturnType - Psalm is not capable of validating the return type yet
 * @ psalm-suppress MixedReturnStatement - Psalm is not capable of validating the return type yet
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function fetch(string $uri, ?FetchConfig $config = null)
{
    return (new FetchClient())($uri, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function get(string $uri, ?FetchConfig $config = null)
{
    return (new FetchClient())->get($uri, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function options(string $uri, ?FetchConfig $config = null)
{
    return (new FetchClient())->options($uri, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function head(string $uri, ?FetchConfig $config = null)
{
    return (new FetchClient())->head($uri, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function delete(string $uri, ?FetchConfig $config = null)
{
    return (new FetchClient())->delete($uri, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param Data $data
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function post(string $uri, mixed $data, ?FetchConfig $config = null)
{
    return (new FetchClient())->post($uri, $data, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param Data $data
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function put(string $uri, mixed $data, ?FetchConfig $config = null)
{
    return (new FetchClient())->put($uri, $data, $config);
}

/**
 * @template Data
 * @template TransportRequest
 * @template TransportResponse
 *
 * @param Data $data
 * @param FetchConfig<Data, TransportRequest, TransportResponse>|null $config
 *
 * @return ($config is null ? ResponseInterface : (TransportResponse is mixed ? ResponseInterface : TransportResponse))
 */
function patch(string $uri, mixed $data, ?FetchConfig $config = null)
{
    return (new FetchClient())->patch($uri, $data, $config);
}
