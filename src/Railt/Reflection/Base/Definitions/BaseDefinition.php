<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Definitions;

use Railt\Reflection\Base\Behavior\BaseDeprecations;
use Railt\Reflection\Base\Resolving;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Document;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseDefinition implements Definition, \JsonSerializable
{
    use Resolving;
    use BaseDeprecations;

    /**
     * Type definition name
     */
    protected const TYPE_NAME = '';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @param mixed $value
     * @return array|string
     */
    protected function formatValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (\is_scalar($value)) {
            return $value;
        }

        if (\is_iterable($value)) {
            $result = [];

            foreach ($value as $key => $sub) {
                $result[$key] = $this->formatValue($sub);
            }

            return $result;
        }

        return $this->getTypeName() . '(' . $this->getName() . ')#' . $this->getUniqueId();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->resolve()->formatValue($this);
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $data = $this->resolve()->__sleep();
        $result = [];

        foreach ($data as $fieldName) {
            $result[$fieldName] = $this->formatValue($this->$fieldName);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return [
            // Self
            'id',
            'document',

            // interface Nameable
            'name',
            'description',

            // trait BaseDeprecations
            'deprecationReason',
        ];
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        \assert(static::TYPE_NAME !== '', 'Type name must be initialized');

        return static::TYPE_NAME;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        \assert($this->name !== null,
            \sprintf('Name of %s must be initialized.', \get_class($this)));

        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getUniqueId(): string
    {
        if ($this->id === null) {
            $this->id = \spl_object_hash($this);
        }

        return $this->id;
    }
}
