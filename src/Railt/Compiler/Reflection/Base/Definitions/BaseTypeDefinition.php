<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseTypeDefinition extends BaseDefinition implements TypeDefinition
{
    /**
     * Type definition name
     */
    protected const TYPE_NAME = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        \assert(static::TYPE_NAME !== '', 'Type name must be initialized');

        return static::TYPE_NAME;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            'description'
        ]);
    }
}
