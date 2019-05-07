<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Schema;

use Phplrt\Parser\ParserInterface;
use Railt\SDL\Reflection\Coercion\TypeCoercion;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Reflection\Validation\Base\ValidatorInterface;
use Railt\SDL\Runtime\CallStackInterface;

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
