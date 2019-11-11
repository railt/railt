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
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;
use Railt\TypeSystem\Common\DefaultValueTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\TypeTrait;

/**
 * @method InputTypeInterface getType()
 */
class InputField extends Definition implements InputFieldInterface
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
