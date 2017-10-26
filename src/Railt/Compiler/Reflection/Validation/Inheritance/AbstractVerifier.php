<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance;

use Railt\Compiler\Reflection\Support;
use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Exceptions\TypeRedefinitionException;

/**
 * Class AbstractVerifier
 */
abstract class AbstractVerifier implements Verifier
{
    use Support;

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

    /**
     * @param string $message
     * @param array ...$args
     * @return bool
     * @throws TypeRedefinitionException
     */
    protected function throw(string $message, ...$args): bool
    {
        throw new TypeRedefinitionException(\sprintf($message, ...$args));
    }
}
