<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Compiler;

use Hoa\Compiler\Llk\TreeNode;
use Hoa\Compiler\Visitor\Dump;
use PHPUnit\Framework\Assert;
use Serafim\Railgun\Compiler\Compiler;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Compiler\Exceptions\NotReadableException;
use Serafim\Railgun\Compiler\Exceptions\UnexpectedTokenException;

/**
 * Class CompilerTestCase
 * @package Serafim\Railgun\Tests\Compiler
 */
class CompilerTestCase extends AbstractTestCase
{
    /**
     * @param string $file
     * @return TreeNode
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     */
    private function read(string $file): TreeNode
    {
        return (new Compiler())
            ->parseFile(__DIR__ . '/../.resources/' . $file)
            ->getAst();
    }

    /**
     * @param TreeNode $ast
     */
    private function dump(TreeNode $ast)
    {
        dd((new Dump())->visit($ast));
    }

    /**
     * @return array
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     */
    public function typeDefinitionsProviders(): array
    {
        return [
            [ $this->read('typedef1.graphqls') ],
            [ $this->read('typedef2.graphqls') ],
            [ $this->read('typedef3.graphqls') ],
            [ $this->read('typedef4.graphqls') ],
            [ $this->read('typedef5.graphqls') ],
        ];
    }

    /**
     * @dataProvider typeDefinitionsProviders
     *
     * @param TreeNode $ast
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeDefinition(TreeNode $ast): void
    {
        Assert::assertEquals('#Document', $ast->getId());
        Assert::assertCount(1, $ast->getChildren());

        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            Assert::assertEquals('#Type', $child->getId());
            Assert::assertEquals('T_NAME', $child->getChild(0)->getValueToken());
            Assert::assertEquals('A', $child->getChild(0)->getValueValue());
        }
    }

    /**
     * @throws NotReadableException
     * @throws UnexpectedTokenException
     * @throws \PHPUnit\Framework\Exception
     */
    public function testTypeDefinitionWithInterfaceImplementation()
    {
        $ast  = $this->read('typedef2.graphqls');

        $type = $ast->getChild(0);
        $implements = $type->getChild(1);

        Assert::assertEquals('#Type', $type->getId());
        Assert::assertEquals('T_NAME', $type->getChild(0)->getValueToken());

        Assert::assertEquals('#Implements', $implements->getId());
        foreach (['B', 'C'] as $i => $name) {
            Assert::assertEquals($name, $implements->getChild($i)->getValueValue());
        }
    }
}
