<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions;

use Railt\Compiler\Reflection\Base\Behavior\BaseDeprecations;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Reflection\Support;

/**
 * Class BaseTypeDefinition
 */
abstract class BaseDefinition implements Definition, \JsonSerializable
{
    use Support;
    use BaseDeprecations;

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
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->typeToString($this);
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
        $data   = $this->__sleep();
        $result = [];

        foreach ($data as $fieldName) {
            $result[$fieldName] = $this->valueToScalar($this->$fieldName);
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
    public function getName(): string
    {
        \assert($this->name !== null,
            \sprintf('Name of %s must be initialized.', \get_class($this)));

        return (string)$this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
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

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->getDocument()->getFileName();
    }
}
