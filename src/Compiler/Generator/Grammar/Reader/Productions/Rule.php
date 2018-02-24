<?php
/**
 * This file is part of railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Generator\Grammar\Reader\Productions;

/**
 * Class Rule
 */
class Rule
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @param string $name
     */
    public function rename(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function is(string $name): bool
    {
        return static::class === $name;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function __debugInfo(): array
    {
        $props = (new \ReflectionClass(static::class))
            ->getProperties(\ReflectionProperty::IS_PROTECTED);

        $result = [];

        foreach ($props as $prop) {
            $name = $prop->getName();
            $result[$name] = $this->$name;
        }

        return $result;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function __toString(): string
    {
        $result = \class_basename($this);

        foreach ($this->__debugInfo() as $name => $value) {
            if ($value === null) {
                continue;
            }

            if (\is_array($value)) {
                foreach ($value as $sub) {
                    $lines = \explode("\n", (string)$sub);
                    foreach ($lines as $line) {
                        $result .= "\n    " . $line;
                    }
                }
            } else {
                if (\is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }

                $result .= ' ' . $name . ' = "' . $value . '"';
            }
        }

        return $result;
    }
}
