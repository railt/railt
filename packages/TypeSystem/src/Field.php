<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\Constraint;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\Type\OutputTypeInterface;
use Railt\TypeSystem\Common\ArgumentsTrait;
use Railt\TypeSystem\Common\DeprecationTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\TypeTrait;

/**
 * @method OutputTypeInterface getType()
 */
class Field extends Definition implements FieldInterface
{
    use NameTrait;
    use ArgumentsTrait;
    use DescriptionTrait;
    use DeprecationTrait;
    use TypeTrait {
        TypeTrait::assertTypeTrait as private _assertTypeTrait;
    }

    /**
     * @return void
     */
    protected function assertTypeTrait(): void
    {
        \assert(Constraint::isOutputType($this->type));
    }
}
