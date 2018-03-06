<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Interface InputInterface
 */
interface InputInterface
{
    public const DEPTH_DELIMITER = '.';

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
     * @return string
     */
    public function getOperation(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getFieldName(): string;

    /**
     * @return FieldDefinition
     */
    public function getFieldDefinition(): FieldDefinition;

    /**
     * @return string
     */
    public function getAlias(): string;

    /**
     * @return bool
     */
    public function hasAlias(): bool;

    /**
     * @return mixed
     */
    public function getParent();

    /**
     * @return mixed
     */
    public function getParentResponse();

    /**
     * @param mixed $parent
     * @param mixed $parentResponse
     * @return void
     */
    public function updateParent($parent, $parentResponse): void;
}
