<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Extension;

use Railt\Contracts\Extension\MutableExtensionInterface;

/**
 * Class Extension
 */
class Extension implements MutableExtensionInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Extension constructor.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $name
     * @return MutableExtensionInterface|$this
     */
    public function rename(string $name): MutableExtensionInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $value
     * @return MutableExtensionInterface|$this
     */
    public function update($value): MutableExtensionInterface
    {
        $this->value = $value;

        return $this;
    }
}
