<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem;

use GraphQL\Contracts\TypeSystem\Type\InputTypeInterface;
use Railt\TypeSystem\Common\NameTrait;
use Railt\TypeSystem\Common\TypeTrait;
use GraphQL\Contracts\TypeSystem\Constraint;
use Railt\TypeSystem\Common\ArgumentsTrait;
use Railt\TypeSystem\Common\DescriptionTrait;
use Railt\TypeSystem\Common\DeprecationTrait;
use GraphQL\Contracts\TypeSystem\FieldInterface;
use GraphQL\Contracts\TypeSystem\Type\TypeInterface;
use GraphQL\Contracts\TypeSystem\Type\OutputTypeInterface;

/**
 * {@inheritDoc}
 */
class Field extends Definition implements FieldInterface
{
    use NameTrait;
    use ArgumentsTrait;
    use DescriptionTrait;
    use DeprecationTrait;
    use TypeTrait {
        setType as private setOutputType;
        getType as private getOutputType;
    }

    /**
     * @var string
     */
    private const ERROR_TYPE_INVARIANT_VIOLATION = '%s must be initialized by %s';

    /**
     * @var string
     */
    private const ERROR_TYPE_PRECONDITION_VIOLATION = 'Type of Field must be an instance of %s, but %s given';

    /**
     * {@inheritDoc}
     */
    public function getType(): TypeInterface
    {
        $type = $this->getOutputType();

        \assert(Constraint::isOutputType($type), $this->typeInvariantErrorMessage());

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
        \assert(Constraint::isOutputType($type), $this->typePreconditionErrorMessage($type));

        $this->setOutputType($type);
    }

    /**
     * @return string
     */
    private function typeInvariantErrorMessage(): string
    {
        return \sprintf(self::ERROR_TYPE_INVARIANT_VIOLATION, \get_class($this), OutputTypeInterface::class);
    }

    /**
     * @param TypeInterface $type
     * @return string
     */
    private function typePreconditionErrorMessage(TypeInterface $type): string
    {
        return \sprintf(self::ERROR_TYPE_PRECONDITION_VIOLATION, OutputTypeInterface::class, \get_class($type));
    }
}
