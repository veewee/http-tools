<?php

declare(strict_types=1);

namespace Phpro\HttpTools\Fetch;

use Phpro\HttpTools\Request\RequestInterface;

/**
 * @psalm-immutable
 *
 * @template Data
 *
 * @psalm-import-type Method from RequestInterface
 */
final class FetchParams
{
    /**
     * @var Method
     */
    public string $method;

    /**
     * @var array<string, string>
     */
    public array $headers;

    /**
     * @var Data
     */
    public mixed $data;

    /**
     * @param Method $method
     * @param array<string, string> $headers
     * @param Data $data
     */
    private function __construct(
        string $method,
        array $headers = [],
        mixed $data = null,
    ) {
        $this->method = $method;
        $this->headers = $headers;
        $this->data = $data;
    }

    /**
     * @psalm-pure
     * @return FetchParams<null>
     */
    public static function empty(): self
    {
        return new self(
            method: 'GET',
            headers: [],
            data: null,
        );
    }

    /**
     * @psalm-pure
     * @template NewData
     *
     * @param Method|null $method
     * @param array<string, string> $headers
     * @param NewData|null $data
     *
     * @return ($data is null ? FetchParams<NewData> : FetchParams<null>)
     */
    public static function of(
        ?string $method = null,
        array $headers = [],
        mixed $data = null,
    ): self {
        return new self(
            method: $method ?? 'GET',
            headers: $headers,
            data: $data,
        );
    }
}
