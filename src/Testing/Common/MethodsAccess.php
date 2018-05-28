<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing\Common;

/**
 * Trait MethodsAccess
 */
trait MethodsAccess
{
    /**
     * @param string $field
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get(string $field)
    {
        if (\method_exists($this, $field)) {
            return \call_user_func([$this, $field]);
        }

        if (\property_exists($this, $field)) {
            return $this->$field;
        }

        throw new \InvalidArgumentException('Field ' . $field . ' does not exist');
    }
}
