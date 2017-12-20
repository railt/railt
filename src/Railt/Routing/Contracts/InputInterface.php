<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Contracts;

use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Interface InputInterface
 */
interface InputInterface
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
     * @return TypeDefinition
     */
    public function getType(): TypeDefinition;

    /**
     * @return string
     */
    public function getQueryType(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @return bool
     */
    public function hasAlias(): bool;
}
