<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

use Serafim\Railgun\Schema\Definitions\ArgumentDefinitionInterface;
use Serafim\Railgun\Schema\Definitions\FieldDefinitionInterface;
use Serafim\Railgun\Support\NameableInterface;

/**
 * Trait BuilderHelpers
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
trait BuilderHelpers
{
    /**
     * @param NameableInterface $target
     * @param array $data
     * @return array
     */
    public static function withName(NameableInterface $target, array $data = []): array
    {
        return array_merge(['name' => $target->getName()], $data);
    }

    /**
     * @param NameableInterface $target
     * @param array $data
     * @return array
     */
    public static function withDescription(NameableInterface $target, array $data = []): array
    {
        return array_merge(['description' => $target->getDescription()], $data);
    }

    /**
     * @param NameableInterface $target
     * @param array $data
     * @return array
     */
    public static function withInfo(NameableInterface $target, array $data = []): array
    {
        return static::withName($target, static::withDescription($target, $data));
    }

    /**
     * @param FieldDefinitionInterface $target
     * @param array $data
     * @return array
     */
    public static function withDeprecation(FieldDefinitionInterface $target, array $data = []): array
    {
        if ($target->isDeprecated()) {
            return array_merge(['deprecationReason' => $target->getDeprecationReason()], $data);
        }

        return $data;
    }
}
