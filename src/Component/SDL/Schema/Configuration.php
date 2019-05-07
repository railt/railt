<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Schema;

use Railt\Component\Parser\ParserInterface;
use Railt\Component\SDL\Reflection\Coercion\TypeCoercion;
use Railt\Component\SDL\Reflection\Dictionary;
use Railt\Component\SDL\Reflection\Validation\Base\ValidatorInterface;
use Railt\Component\SDL\Runtime\CallStackInterface;

/**
 * Interface Configuration
 */
interface Configuration
{
    /**
     * @return CallStackInterface
     */
    public function getCallStack(): CallStackInterface;

    /**
     * @param string $group
     * @return ValidatorInterface
     */
    public function getValidator(string $group): ValidatorInterface;

    /**
     * @return TypeCoercion
     */
    public function getTypeCoercion(): TypeCoercion;

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface;

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary;
}
