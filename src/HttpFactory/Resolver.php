<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory;

use Railt\Http\Request;

/**
 * Class Resolver
 */
abstract class Resolver implements ResolverInterface
{
    /**
     * @param array $data
     * @return array
     */
    protected function variables(array $data): array
    {
        return (array)($data[Request::FIELD_VARIABLES] ?? []);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function match(array $data): bool
    {
        return $this->exists($data) &&
            \is_string($data[Request::FIELD_QUERY]);
    }

    /**
     * @param array $data
     * @return bool
     */
    private function exists(array $data): bool
    {
        return isset($data[Request::FIELD_QUERY]) ||
            \array_key_exists(Request::FIELD_QUERY, $data);
    }

    /**
     * @param array $data
     * @return string
     */
    protected function query(array $data): string
    {
        return $data[Request::FIELD_QUERY];
    }
}
