<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Input;

/**
 * Trait HasType
 * @mixin ProvideType
 */
trait HasType
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return $this->type;
    }

    /**
     * @param string $name
     * @return ProvideType|$this
     */
    public function withTypeName(string $name): ProvideType
    {
        $this->type = $name;

        return $this;
    }
}
