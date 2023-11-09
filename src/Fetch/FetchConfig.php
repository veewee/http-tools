<?php

declare(strict_types=1);

namespace Phpro\HttpTools\Fetch;

use Http\Client\Common\Plugin;
use Http\Discovery\Psr18ClientDiscovery;
use Phpro\HttpTools\Transport\Presets\PsrPreset;
use Phpro\HttpTools\Transport\TransportInterface;
use Phpro\HttpTools\Uri\RawUriBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @template TransportRequest
 * @template TransportResponse
 *
 * @psalm-immutable
 */
final class FetchConfig
{
    /**
     * @var (\Closure(ClientInterface): TransportInterface<TransportRequest, TransportResponse>)
     */
    public readonly \Closure $transport;

    /**
     * @param list<Plugin> $plugins
     * @param (callable(ClientInterface): TransportInterface<TransportRequest, TransportResponse>) $transport
     */
    private function __construct(
        public readonly ClientInterface $client,
        public readonly array $plugins,
        callable $transport,
    ) {
        $this->transport = $transport(...);
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress MixedReturnTypeCoercion
     *
     * @return FetchConfig<string|null, ResponseInterface>
     */
    public static function defaults(): self
    {
        /** @psalm-suppress ImpureMethodCall - Lets assume Psr-18 client discovery is not mutating this class */

        return new self(
            client: Psr18ClientDiscovery::find(),
            plugins: [],
            transport: self::defaultTransport(),
        );
    }

    /**
     * @psalm-pure
     *
     * @template NewTransportRequest
     * @template NewTransportResponse
     *
     * @param list<Plugin> $plugins
     * @param (callable(ClientInterface): TransportInterface<NewTransportRequest, NewTransportResponse>)|null $transport
     *
     * @return FetchConfig<
     *     ($transport is null ? string|null : NewTransportRequest),
     *     ($transport is null ? ResponseInterface : NewTransportResponse)
     * >
     *
     * @psalm-suppress InvalidArgument - Psalm gets lost here.
     */
    public static function of(
        ?ClientInterface $client = null,
        array $plugins = [],
        $transport = null,
    ): self {
        /** @psalm-suppress ImpureMethodCall - Lets assume Psr-18 client discovery is not mutating this class */
        return new self(
            client: $client ?? Psr18ClientDiscovery::find(),
            plugins: $plugins,
            transport: $transport ?? self::defaultTransport(),
        );
    }

    /**
     * @psalm-pure
     *
     * @return callable(ClientInterface): TransportInterface<string|null, ResponseInterface>
     */
    public static function defaultTransport()
    {
        return static fn (ClientInterface $client) => PsrPreset::create(
            $client,
            RawUriBuilder::createWithAutodiscoveredPsrFactories()
        );
    }
}
