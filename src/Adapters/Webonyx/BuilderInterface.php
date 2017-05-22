<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use GraphQL\Type\Definition\Type;
use Serafim\Railgun\Adapters\Webonyx\Builders\TypesBuilder;
use Serafim\Railgun\Contracts\ProvidesTypeRegistryInterface;
use Serafim\Railgun\Adapters\Webonyx\Builders\PartialsBuilder;

/**
 * Interface BuilderInterface
 * @package Serafim\Railgun\Adapters\Webonyx
 */
interface BuilderInterface extends ProvidesTypeRegistryInterface
{
    /**
     * @return PartialsBuilder
     */
    public function getPartialsBuilder(): PartialsBuilder;

    /**
     * @return TypesBuilder
     */
    public function getTypesBuilder(): TypesBuilder;

    /**
     * @param string $type
     * @return Type
     */
    public function type(string $type): Type;

    /**
     * @return iterable|Type[]
     */
    public function getTypes(): iterable;
}
