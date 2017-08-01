<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Compiler\Exceptions\CompilerException;
use Serafim\Railgun\Reflection\Abstraction\Common\HasFieldsInterface;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Document;
use Serafim\Railgun\Reflection\Field;

/**
 * Trait Fields
 * @package Serafim\Railgun\Reflection\Common
 * @mixin HasFieldsInterface
 */
trait Fields
{
    /**
     * @var array|FieldInterface[]
     */
    private $fields = [];

    /**
     * @param Document $document
     * @param TreeNode $ast
     */
    protected function compileFields(Document $document, TreeNode $ast): void
    {
        $allowed = in_array($ast->getId(), (array)($this->astHasFields ?? ['#Field']), true);

        if ($allowed) {
            $field = new Field($this->getDocument(), $ast);
            $this->fields[$field->getName()] = $field;
        }
    }

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
}
