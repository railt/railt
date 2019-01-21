<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Output\HasData;

/**
 * Class Output
 */
class Output implements OutputInterface
{
    use HasData;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var mixed
     */
    private $value;

    /**
     * Output constructor.
     *
     * @param mixed|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return Output
     */
    public static function new($value = null): self
    {
        return new static($value);
    }

    /**
     * @param mixed|OutputInterface $value
     * @return mixed
     */
    public static function unwrap($value)
    {
        $result = $value instanceof OutputInterface ? $value->getValue() : $value;

        if (\is_iterable($result)) {
            $unpacked = [];

            foreach ($result as $key => $child) {
                $unpacked[$key] = static::unwrap($child);
            }

            return $unpacked;
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @return Output
     */
    public function update($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $type
     * @return $this|Output
     */
    public function of(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getTypeName(): ?string
    {
        return $this->type;
    }
}
