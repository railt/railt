<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Introspection;

/**
 * Class Introspection
 */
class Introspection implements \JsonSerializable
{
    /**
     * @var string
     */
    private const INTROSPECTION_QUERY_FILE = __DIR__ . '/../resources/introspection.graphql';

    /**
     * @var string
     */
    private string $source;

    /**
     * Introspection constructor.
     */
    public function __construct()
    {
        $this->source = \file_get_contents(self::INTROSPECTION_QUERY_FILE);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'query' => $this->source
        ];
    }
}
