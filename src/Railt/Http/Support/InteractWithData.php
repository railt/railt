<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Support;

/**
 * Trait InteractWithData
 */
trait InteractWithData
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->data[$this->getQueryArgument()] ?? '{}';
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        $key = $this->getVariablesArgument();

        if (\array_key_exists($this->getVariablesArgument(), $this->data)) {
            return (array)$this->data[$key];
        }

        return [];
    }

    /**
     * @return null|string
     */
    public function getOperation(): ?string
    {
        return $this->data[$this->getOperationArgument()] ?? null;
    }

    /**
     * @param string $field
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $field, $default = null)
    {
        return $this->data[$field] ?? $default;
    }

    /**
     * @param string $field
     * @return bool
     */
    public function has(string $field): bool
    {
        return \array_key_exists($field, $this->data);
    }
}
