<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node\Common;

/**
 * Trait ReadOnlyAttributesTrait
 */
trait ReadOnlyAttributesTrait
{
    /**
     * @param string $name
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function __get(string $name)
    {
        if (\method_exists($this, $method = 'get' . \ucfirst($name))) {
            return $this->$method();
        }

        if (\property_exists($this, $name)) {
            return $this->$name;
        }

        $message = \sprintf('Undefined property: %s::%s', static::class, $name);

        throw new \OutOfBoundsException($message);
    }
}
