<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Definitions;

use Serafim\Railgun\Support\NameableInterface;

/**
 * Interface FieldDefinitionInterface
 * @package Serafim\Railgun\Schema\Definitions
 */
interface FieldDefinitionInterface extends
    NameableInterface,
    ProvidesTypeDefinitionInterface
{
    /**
     * @return iterable|ArgumentDefinitionInterface[]
     */
    public function getArguments(): iterable;

    /**
     * @return bool
     */
    public function isResolvable(): bool;

    /**
     * @param array $arguments
     * @return mixed
     */
    public function resolve(array $arguments = []);

    /**
     * @return bool
     */
    public function isDeprecated(): bool;

    /**
     * @return string
     */
    public function getDeprecationReason(): string;
}
