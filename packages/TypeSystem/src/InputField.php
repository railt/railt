<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\TypeTrait;
use GraphQL\Contracts\TypeSystem\Constraint;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\DefaultValueTrait;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\InputFieldInterface;
use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;

/**
 * {@inheritDoc}
 */
class InputField extends Definition implements InputFieldInterface
{
    use NameTrait;
    use DescriptionTrait;
    use DefaultValueTrait;
    use TypeTrait {
        setType as private setInputType;
        getType as private getInputType;
    }

    /**
     * @var string
     */
    private const ERROR_TYPE_INVARIANT_VIOLATION = '%s must be initialized by %s';

    /**
     * @var string
     */
    private const ERROR_TYPE_PRECONDITION_VIOLATION = 'Type of InputField must be an instance of %s, but %s given';

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        $type = $this->getInputType();

        \assert(Constraint::isInputType($type), $this->typeInvariantErrorMessage());

        return $type;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param TypeInterface $type
     * @return void
     */
    public function setType(TypeInterface $type): void
    {
        \assert(Constraint::isInputType($type), $this->typePreconditionErrorMessage($type));

        $this->setInputType($type);
    }

    /**
     * @return string
     */
    private function typeInvariantErrorMessage(): string
    {
        return \sprintf(self::ERROR_TYPE_INVARIANT_VIOLATION, \get_class($this), InputTypeInterface::class);
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
    private function typePreconditionErrorMessage(TypeInterface $type): string
    {
        return \sprintf(self::ERROR_TYPE_PRECONDITION_VIOLATION, InputTypeInterface::class, \get_class($type));
    }
}
