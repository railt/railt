<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Behavior;

use Railt\Reflection\Contracts\Behavior\Nameable;

/**
 * Trait BaseName
 * @mixin Nameable
 */
trait BaseName
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }
}
