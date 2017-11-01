<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Base\Invocations\Directive\BaseDirectivesContainer;
use Railt\Compiler\Reflection\Contracts\Definitions\ScalarDefinition;

/**
 * Class BaseScalar
 */
abstract class BaseScalar extends BaseTypeDefinition implements ScalarDefinition
{
    use BaseDirectivesContainer;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Scalar';
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
