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

/**
 * Trait HasFields
 * @package Serafim\Railgun\Reflection\Common
 * @mixin HasFieldsInterface
 */
trait HasFields
{
    /**
     * @var array|FieldInterface[]
     */
    private $fields = [];

    /**
     * @param TreeNode $ast
     * @param Dictionary $dictionary
     */
    protected function compileHasFields(TreeNode $ast, Dictionary $dictionary): void
    {
        $allowed = in_array($ast->getId(), $this->astHasFields ?? ['#Field'], true);

        if ($allowed) {
            throw new CompilerException('TODO: Add fields compilation for ' . get_class($this));
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
