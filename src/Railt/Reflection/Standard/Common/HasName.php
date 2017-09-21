<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Common;

use Railt\Reflection\Contracts\Behavior\Nameable;

/**
 * Trait HasName
 * @mixin Nameable
 */
trait HasName
{
    /**
     * @var string|null
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
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
