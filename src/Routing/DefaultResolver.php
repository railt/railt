<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing;

use Railt\Container\ContainerInterface;
use Railt\Http\InputInterface;
use Railt\Reflection\Contracts\Definitions\EnumDefinition;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Railt\Reflection\Contracts\Dependent\FieldDefinition;

/**
 * Class DefaultResolver
 */
class DefaultResolver
{
    /**
     * @param ContainerInterface $app
     * @return \Closure
     */
    public static function toClosure(ContainerInterface $app): \Closure
    {
        return \Closure::fromCallable($app->make(static::class));
    }

    /**
     * @param InputInterface $input
     * @param FieldDefinition $field
     * @return mixed
     */
    public function __invoke(InputInterface $input, FieldDefinition $field)
    {
        if ($this->isScalar($field)) {
            return $this->getScalarResponse($input);
        }

        if (! $field->isNonNull()) {
            return null;
        }

        return $this->fromParent($input) ?? [];
    }

    /**
     * @param InputInterface $input
     * @return mixed
     */
    private function getScalarResponse(InputInterface $input)
    {
        return $this->fromParent($input);
    }

    /**
     * @param InputInterface $input
     * @return mixed
     */
    private function fromParent(InputInterface $input)
    {
        $parent = $input->getParentResponse();

        if ($parent) {
            return $parent[$input->getFieldName()];
        }

        return null;
    }

    /**
     * @param FieldDefinition $field
     * @return bool
     */
    private function isScalar(FieldDefinition $field): bool
    {
        $type = $field->getTypeDefinition();

        return $type instanceof ScalarDefinition || $type instanceof EnumDefinition;
    }
}
