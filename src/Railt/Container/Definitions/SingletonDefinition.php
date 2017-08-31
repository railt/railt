<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Definitions;

/**
 * Class SingletonDefinition
 * @package Railt\Container\Definitions
 */
class SingletonDefinition extends FactoryDefinition
{
    /**
     * @var bool
     */
    private $resolved = false;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @return mixed
     * @throws \LogicException
     */
    public function resolve()
    {
        if ($this->resolved === false) {
            $this->value = parent::resolve();
            $this->resolved = true;
        }

        return $this->value;
    }
}
