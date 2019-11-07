<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem;

use Railt\SDL\TypeSystem\Common\NameTrait;
use Railt\SDL\TypeSystem\Common\TypeTrait;
use GraphQL\Contracts\TypeSystem\Constraint;
use Railt\SDL\TypeSystem\Common\DescriptionTrait;
use Railt\SDL\TypeSystem\Common\DefaultValueTrait;
use GraphQL\Contracts\TypeSystem\ArgumentInterface;
use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;

/**
 * @method InputTypeInterface getType()
 */
class Argument extends Definition implements ArgumentInterface
{
    use NameTrait;
    use DescriptionTrait;
    use DefaultValueTrait;
    use TypeTrait {
        TypeTrait::assertTypeTrait as private _assertTypeTrait;
    }

    /**
     * @return void
     */
    protected function assertTypeTrait(): void
    {
        \assert(Constraint::isInputType($this->type));
    }
}
