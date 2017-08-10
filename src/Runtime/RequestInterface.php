<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

/**
 * Interface RequestInterface
 * @package Serafim\Railgun\Runtime
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function all(): array;

    /**
     * @param string $argument
     * @param null $default
     * @return mixed
     */
    public function get(string $argument, $default = null);

    /**
     * @param string $argument
     * @return bool
     */
    public function has(string $argument): bool;

    /**
     * @return iterable
     */
    public function getRelations(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasRelation(string $name): bool;

    /**
     * @return string
     */
    public function getPath(): string;
}
