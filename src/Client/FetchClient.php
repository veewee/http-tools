<?php

declare(strict_types=1);

namespace Phpro\HttpTools\Client;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderSetPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr18ClientDiscovery;
use Phpro\HttpTools\Request\Request;
use Phpro\HttpTools\Transport\Presets\PsrPreset;
use Phpro\HttpTools\Uri\RawUriBuilder;
use function Psl\Dict\merge;
use Psr\Http\Client\ClientInterface;

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
 * @template RequestData
 * @template ResponseData
 * @psalm-type PartialConfig = array{
 *   method ?: 'POST'|'GET'|'DELETE'|'PATCH'|'PUT'|'OPTIONS'|'HEAD',
 *   headers ?: array<string, string>,
 *   data ?: RequestData
 *   transport ?: (client: ClientInterface) => TransportInterface<RequestData, ResponseData>
 *   client ?: ClientInterface
 *   plugins ?: list<Plugin>
 * }
 * @psalm-type Config = array{
 *   method: 'POST'|'GET'|'DELETE'|'PATCH'|'PUT'|'OPTIONS'|'HEAD',
 *   headers: array<string, string>,
 *   data: RequestData
 *   transport: (client: ClientInterface) => TransportInterface<RequestData, ResponseData>
 *   client: ClientInterface
 *   plugins ?: list<Plugin>
 * }
 */
final class FetchClient
{
    /**
     * @var PartialConfig
     */
    private array $config;

    /**
     * @param PartialConfig $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param PartialConfig $config
     *
     * @return ResponseData
     */
    public function __invoke(string $uri, array $config)
    {
        $allConfig = $this->mergeAllConfigs($config);
        $client = $this->configureClient($allConfig);
        $transport = $allConfig['transport']($client);
        $request = new Request($allConfig['method'], $uri, [], $allConfig['data']);

        return $transport($request);
    }

    /**
     * @param PartialConfig $config
     *
     * @return ResponseData
     */
    public function get(string $uri, array $config)
    {
        return ($this)($uri, $config);
    }

    /**
     * @param PartialConfig $config
     *
     * @return ResponseData
     */
    public function options(string $uri, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'OPTIONS',
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     *
     * @return ResponseData
     */
    public function head(string $uri, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'OPTIONS',
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     *
     * @return ResponseData
     */
    public function delete(string $uri, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'DELETE',
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     * @param RequestData $data
     *
     * @return ResponseData
     */
    public function post(string $uri, mixed $data, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'POST',
                'data' => $data,
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     * @param RequestData $data
     *
     * @return ResponseData
     */
    public function put(string $uri, mixed $data, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'PUT',
                'data' => $data,
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     * @param RequestData $data
     *
     * @return ResponseData
     */
    public function patch(string $uri, mixed $data, array $config)
    {
        return ($this)($uri, merge(
            $config,
            [
                'method' => 'PATCH',
                'data' => $data,
            ]
        ));
    }

    /**
     * @param PartialConfig $config
     *
     * @return Config
     */
    private function mergeAllConfigs(array $config): array
    {
        // TODO : merge deep for headers and plugins?
        return merge(
            [
                'method' => 'GET',
                'headers' => [],
                'data' => null,
                'plugins' => [],
                'client' => Psr18ClientDiscovery::find(),
                'transport' => fn(ClientInterface $client) => PsrPreset::sync(
                    $client,
                    RawUriBuilder::createWithAutodiscoveredPsrFactories()
                ),
            ],
            $this->config,
            $config
        );
    }

    /**
     * @param Config $config
     */
    private function configureClient(array $config): ClientInterface
    {
        return new PluginClient(
            $config['client'],
            [
                new ErrorPlugin(),
                ...($config['headers'] ? [new HeaderSetPlugin($config['headers'])] : []),
                ...($config['plugins'])
            ]
        );
    }
}
