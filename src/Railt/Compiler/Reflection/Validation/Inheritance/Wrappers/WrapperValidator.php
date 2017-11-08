<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Inheritance\Wrappers;

use Railt\Compiler\Reflection\Contracts\Behavior\AllowsTypeIndication;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;

/**
 * Interface WrapperValidator
 */
interface WrapperValidator extends ValidatorInterface
{
    /**
     * @param AllowsTypeIndication $type
     * @return bool
     */
    public function match($type): bool;

    /**
     * @param AllowsTypeIndication $type Parent type
     * @param AllowsTypeIndication $overridenBy Children type
     * @param bool $direct Is direct overriding or inverse
     * @return void
     */
    public function validate(AllowsTypeIndication $type, AllowsTypeIndication $overridenBy, bool $direct = true): void;
}
