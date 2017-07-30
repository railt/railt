<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Reflection\Support;

use Hoa\Compiler\Llk\TreeNode;

/**
 * Trait HasName
 * @package Serafim\Railgun\Compiler\Reflection\Support
 */
trait HasName
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @param TreeNode $ast
     */
    private function compileName(TreeNode $ast): void
    {
        $name = $ast->getChild(0);

        if ($name && $name->getId() === '#Name') {
            $this->name = $name->getChild(0)->getValueValue();
        }
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
