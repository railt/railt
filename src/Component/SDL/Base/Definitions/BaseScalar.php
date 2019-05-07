<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Base\Definitions;

use Railt\Component\SDL\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Component\SDL\Contracts\Definitions\ScalarDefinition;
use Railt\Component\SDL\Contracts\Type;

/**
 * Class BaseScalar
 */
abstract class BaseScalar extends BaseTypeDefinition implements ScalarDefinition
{
    use BaseDirectivesContainer;

    /**
     * Object type name
     */
    protected const TYPE_NAME = Type::SCALAR;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return static::TYPE_NAME;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
            // trait HasDirectives
            'directives',
        ]);
    }
}
