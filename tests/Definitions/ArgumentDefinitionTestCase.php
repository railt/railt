<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Definitions;

use PHPUnit\Framework\Assert;
use Serafim\Railgun\Schema\Definitions\ArgumentDefinition;
use Serafim\Railgun\Schema\Definitions\TypeDefinition;
use Serafim\Railgun\Support\NameableInterface;
use Serafim\Railgun\Tests\AbstractTestCase;
use Serafim\Railgun\Tests\Concerns\ContainsName;

/**
 * Class ArgumentDefinitionTestCase
 * @package Serafim\Railgun\Tests\Definitions
 */
class ArgumentDefinitionTestCase extends AbstractTestCase
{
    use ContainsName;

    /**
     * @return void
     */
    public function testDefaultArgumentValue(): void
    {
        $arg = $this->argumentDefinition();

        Assert::assertNull($arg->getDefaultValue());
    }

    /**
     * @return void
     */
    public function testOverwrittenDefaultArgumentValue(): void
    {
        $expected = random_int(PHP_INT_MIN, PHP_INT_MAX);
        $arg = $this->argumentDefinition($expected);

        Assert::assertEquals($expected, $arg->getDefaultValue());
    }

    /**
     * @return \Traversable
     */
    protected function mockDefaultFormattedName(): \Traversable
    {
        yield 'new name' => 'newname';
    }

    /**
     * @return NameableInterface
     */
    protected function mock(): NameableInterface
    {
        return $this->argumentDefinition()
            ->rename('test');
    }

    /**
     * @param null $default
     * @return ArgumentDefinition
     */
    protected function argumentDefinition($default = null)
    {
        return new ArgumentDefinition(new TypeDefinition('test'), $default);
    }
}
