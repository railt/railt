<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection\Origin;

use Psr\Http\Message\UriInterface;
use Railt\Introspection\Introspection;
use Railt\Introspection\Exception\OriginException;

/**
 * Class UriOrigin
 */
class UriOrigin extends Origin
{
    /**
     * @var array
     */
    private const DEFAULT_CONTEXT_OPTIONS = [
        'method'           => 'POST',
        'header'           => [
            'User-Agent'   => 'Railt Introspection Client',
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ],
        'protocol_version' => '1.1',
        'ignore_errors'    => true,
    ];

    /**
     * @var UriInterface|string
     */
    private $uri;

    /**
     * @var array
     */
    private array $context;

    /**
     * @var \JsonSerializable
     */
    private \JsonSerializable $introspection;

    /**
     * UriOrigin constructor.
     *
     * @param string|UriInterface $uri
     * @param array $context
     * @param \JsonSerializable $introspection
     */
    public function __construct($uri, array $context = [], \JsonSerializable $introspection = null)
    {
        $this->uri = $uri;
        $this->context = \array_merge_recursive(self::DEFAULT_CONTEXT_OPTIONS, $context);
        $this->introspection = $introspection ?? new Introspection();
    }

    /**
     * @return array
     * @throws OriginException
     */
    public function load(): array
    {
        $result = $this->read((string)$this->uri, [
            'http' => \array_merge($this->build($this->context), [
                'content' => $this->encode($this->introspection),
            ]),
            'ssl'  => $this->build($this->context),
        ]);

        return $this->decode((string)$result);
    }

    /**
     * @param array $context
     * @return array
     */
    private function build(array $context): array
    {
        if (isset($context['header']) && \is_iterable($context['header'])) {
            $headers = [];

            foreach ($context['header'] as $name => $header) {
                $headers[] = \implode(': ', [$name, $header]);
            }

            $context['header'] = \implode("\n", $headers);
        }

        return $context;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return \hash('crc32', \implode("\n", [
            $this->uri,
            \json_encode($this->context, \JSON_THROW_ON_ERROR),
            \json_encode($this->introspection, \JSON_THROW_ON_ERROR),
        ]));
    }
}
