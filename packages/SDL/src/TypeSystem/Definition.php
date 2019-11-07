<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\TypeSystem;

use Railt\Dumper\Facade;
use GraphQL\Contracts\TypeSystem\DefinitionInterface;

/**
 * {@inheritDoc}
 */
abstract class Definition implements DefinitionInterface
{
    /**
     * Definition constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        foreach ($properties as $name => $value) {
            try {
                $this->$name = $value;
            } catch (\TypeError $e) {
                $message = \vsprintf('Invalid constructor argument [%s => %s] of %s: %s', [
                    Facade::value($name),
                    Facade::dump($value),
                    Facade::dump($this),
                    $e->getMessage()
                ]);

                throw new \InvalidArgumentException($message);
            }
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return \get_object_vars($this);
    }
}
