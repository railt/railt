<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Inheritance\TypeVerification;

use Railt\Reflection\Builder\Inheritance\ExceptionHelper;
use Railt\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;

/**
 * Class AbstractVerifier
 */
abstract class AbstractVerifier implements Verifier
{
    use ExceptionHelper;

    /**
     * @param bool $throws
     * @return Verifier
     */
    public function throwsOnError(bool $throws = true): Verifier
    {
        $this->throws = $throws;

        return $this;
    }

    /**
     * @param AllowsTypeIndication $a
     * @param AllowsTypeIndication $b
     * @return bool
     */
    public function match(AllowsTypeIndication $a, AllowsTypeIndication $b): bool
    {
        return true;
    }

    /**
     * @param AllowsTypeIndication $container
     * @return Definition
     */
    protected function extract(AllowsTypeIndication $container): Definition
    {
        return $container->getType();
    }
}
