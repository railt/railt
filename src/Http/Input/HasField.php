<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Input;

/**
 * Trait HasField
 * @mixin ProvideField
 */
trait HasField
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var string|null
     */
    protected $alias;

    /**
     * @param array $chunks
     * @return string
     */
    public static function chunksToFieldName(array $chunks): string
    {
        return $chunks[\count($chunks) - 1];
    }

    /**
     * @param string $field
     * @return ProvideField|$this
     */
    public function withField(string $field): ProvideField
    {
        $this->field = $field;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param null|string $alias
     * @return ProvideField|$this
     */
    public function withAlias(?string $alias): ProvideField
    {
        $this->alias = $alias !== $this->getField() ? $alias : null;

        return $this;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        if ($this->field === null) {
            $this->field = static::pathToFieldName($this->path);
        }

        return $this->field;
    }

    /**
     * @param array $chunks
     * @return string
     */
    public static function pathToFieldName(array $chunks): string
    {
        return $chunks[\count($chunks) - 1];
    }

    /**
     * @return bool
     */
    public function hasAlias(): bool
    {
        return $this->alias !== null;
    }
}
