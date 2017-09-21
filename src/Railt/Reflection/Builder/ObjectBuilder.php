<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Builder\Support\Directives;
use Railt\Reflection\Builder\Support\Fields;
use Railt\Reflection\Contracts\Types\InterfaceType;
use Railt\Reflection\Contracts\Types\ObjectType;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class ObjectBuilder
 */
class ObjectBuilder extends AbstractNamedTypeBuilder implements ObjectType
{
    use Fields;
    use Directives;

    protected const AST_ID_IMPLEMENTS = '#Implements';

    /**
     * @var array|InterfaceType[]
     */
    private $interfaces = [];

    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === self::AST_ID_IMPLEMENTS) {
            /** @var TreeNode $child */
            foreach ($ast->getChildren() as $child) {
                $this->compileInterface($child);
            }
        }

        return false;
    }

    /**
     * @param TreeNode $child
     * @return void
     */
    private function compileInterface(TreeNode $child): void
    {
        $interface = $child->getChild(0)->getValueValue();

        $this->interfaces[$interface] = $this->getCompiler()->get($interface);
    }

    /**
     * @return iterable|InterfaceType[]
     */
    public function getInterfaces(): iterable
    {
        return \array_values($this->interfaces);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool
    {
        return \array_key_exists($name, $this->interfaces);
    }

    /**
     * @param string $name
     * @return null|InterfaceType
     */
    public function getInterface(string $name): ?InterfaceType
    {
        return $this->interfaces[$name] ?? null;
    }

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int
    {
        return \count($this->interfaces);
    }

    /**
     * @return string
     */
    public function getTypeName(): string
    {
        return 'Object';
    }
}
