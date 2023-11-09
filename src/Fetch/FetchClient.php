<?php

declare(strict_types=1);

namespace Phpro\HttpTools\Fetch;

use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Phpro\HttpTools\Request\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Webmozart\Assert\Assert;

/**
 * This class is inspired on the JS fetch() function:.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch
 *
 * It also contains aliases, just like axios does:
 * @see https://axios-http.com/docs/api_intro
 *
 * fetch(url[, config])
 * get(url[, config])
 * delete(url[, config])
 * head(url[, config])
 * options(url[, config])
 * post(url[, data[, config]])
 * put(url[, data[, config]])
 * patch(url[, data[, config]])
 *
 * It is linked to this package, so that you can use the transport features as well.
 * This makes it possible to e.g. directly parse JSON inside the fetch() function.
 *
 * @template TransportRequest
 * @template TransportResponse
 */
final class FetchClient
{
    /**
     * @param FetchConfig<TransportRequest, TransportResponse> $config
     */
    private function __construct(
        private FetchConfig $config
    ) {
    }

    /**
     * @return self<string|null, ResponseInterface>
     */
    public static function default(): self
    {
        return new self(
            FetchConfig::defaults()
        );
    }

    /**
     * @template NewTransportRequest
     * @template NewTransportResponse
     *
     * @param FetchConfig<NewTransportRequest, NewTransportResponse> $config
     *
     * @return self<NewTransportRequest, NewTransportResponse>
     */
    public static function configure(FetchConfig $config): self
    {
        return new self($config);
    }

    /**
     * @template CallTimeData
     * @param FetchParams<CallTimeData> $params
     *
     * @return TransportResponse
     */
    public function __invoke(string $uri, FetchParams $params)
    {
        $client = $this->configureClient($params);
        $transport = ($this->config->transport)($client);
        $request = new Request($params->method, $uri, [], $params->data);

        return $transport($request);
    }

    /**
     * @template CallTimeData
     *
     * @param FetchParams<CallTimeData> $params
     *
     * @return TransportResponse
     */
    public function get(string $uri, ?FetchParams $params = null)
    {
        return ($this)($uri, $params ?? FetchParams::empty());
    }

    /**
     * @template CallTimeData
     *
     * @param FetchParams<CallTimeData>|null $config
     *
     * @return TransportResponse
     */
    public function options(string $uri, ?FetchParams $config = null)
    {
        $config ??= FetchParams::empty();

        return ($this)($uri, FetchParams::of(
            method: 'OPTIONS',
            headers: $config->headers,
            data: $config->data,
        ));
    }

    private function configureClient(FetchParams $params): ClientInterface
    {
        return new PluginClient(
            $this->config->client,
            [
                new ErrorPlugin(),
                ...($params->headers ? [new HeaderSetPlugin($params->headers)] : []),
                ...$this->config->plugins,
            ]
        );
    }
}
