<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Abstraction\Common\HasFieldsInterface;
use Railt\Reflection\Abstraction\FieldInterface;
use Railt\Reflection\Abstraction\NamedDefinitionInterface;
use Railt\Reflection\Document;
use Railt\Reflection\Field;

/**
 * Trait Fields
 * @package Railt\Reflection\Common
 * @mixin HasFieldsInterface
 */
trait Fields
{
    /**
     * @var array|FieldInterface[]
     */
    private $fields = [];

    /**
     * @return iterable|FieldInterface
     */
    public function getFields(): iterable
    {
        return array_values($this->fields);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @param string $name
     * @return null|FieldInterface
     */
    public function getField(string $name): ?FieldInterface
    {
        return $this->fields[$name] ?? null;
    }

    /**
     * @param Document $document
     * @param TreeNode $ast
     */
    protected function compileFields(Document $document, TreeNode $ast): void
    {
        $allowed = in_array($ast->getId(), (array)($this->astHasFields ?? ['#Field']), true);

        if ($allowed && $this instanceof NamedDefinitionInterface) {
            $field = new Field($this->getDocument(), $ast, $this);
            $this->fields[$field->getName()] = $field;
        }
    }
}
