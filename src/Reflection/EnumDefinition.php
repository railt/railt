<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Exceptions\IndeterminateBehaviorException;
use Railt\Reflection\Abstraction\EnumTypeInterface;
use Railt\Reflection\Abstraction\EnumValueInterface;
use Railt\Reflection\Common\Directives;
use Railt\Reflection\Common\HasLinkingStageInterface;
use Railt\Reflection\Common\HasName;
use Railt\Reflection\Common\LinkingStage;

/**
 * Class EnumDefinition
 * @package Railt\Reflection
 */
class EnumDefinition extends Definition implements
    EnumTypeInterface,
    HasLinkingStageInterface
{
    use HasName;
    use Directives;
    use LinkingStage;

    public function compile(Document $document, TreeNode $ast): ?TreeNode
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function getValues(): iterable
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function hasValue(string $name): bool
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    public function getValue(string $name): ?EnumValueInterface
    {
        IndeterminateBehaviorException::notImplemented(__METHOD__);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Enum';
    }
}
