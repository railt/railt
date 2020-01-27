<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests\Parser;

use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;
use Phplrt\Source\File;
use PHPUnit\Framework\ExpectationFailedException;
use Railt\SDL\Frontend\Ast\Location;
use Railt\SDL\Frontend\Ast\Value\VariableValueNode;
use Railt\TypeSystem\Value\BooleanValue;
use Railt\TypeSystem\Value\EnumValue;
use Railt\TypeSystem\Value\FloatValue;
use Railt\TypeSystem\Value\InputObjectValue;
use Railt\TypeSystem\Value\IntValue;
use Railt\TypeSystem\Value\ListValue;
use Railt\TypeSystem\Value\NullValue;
use Railt\TypeSystem\Value\StringValue;
use Railt\TypeSystem\Value\ValueInterface;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ValueTestCase
 */
class ValueTestCase extends ParserTestCase
{
    /**
     * @return array
     * @throws \Throwable
     */
    public function valuesDataProvider(): array
    {
        $haystack = [
            'booleanTrue: Boolean = true'    => new BooleanValue(true),
            'booleanFalse: Boolean = false'  => new BooleanValue(false),
            'enumValue: Example = VALUE'     => new EnumValue('VALUE'),
            'float: Float = 0.42'            => new FloatValue(.42),
            'negativeFloat: Float = -0.42'   => new FloatValue(-.42),
            'int: Int = 42'                  => new IntValue(42),
            'negativeInt: Int = -42'         => new IntValue(-42),
            'nullable: Example = null'       => new NullValue(),
            'list: [Int] = [1, 2, 3]'        => new ListValue([
                new IntValue(1),
                new IntValue(2),
                new IntValue(3),
            ]),
            'object: Example = {a: 1, b: 2}' => new InputObjectValue([
                'a' => new IntValue(1),
                'b' => new IntValue(2),
            ]),
            'string: String = "string"'      => new StringValue('string'),
            'variable: String = $var'        => $variable = new VariableValueNode('var'),
        ];

        $varSrc = File::fromSources('input InputObject { variable: String = $var }');
        $variable->loc = new Location($varSrc, 39, 44);

        $result = [];

        foreach ($haystack as $field => $value) {
            $result[] = [\sprintf('input InputObject { %s }', $field), $value];
        }

        return $result;
    }

    /**
     * @dataProvider valuesDataProvider
     *
     * @param string $input
     * @param ValueInterface $value
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws ParserRuntimeExceptionInterface
     * @throws \Throwable
     */
    public function testValues(string $input, ValueInterface $value): void
    {
        $ast = $this->parseFirst($input);

        $this->assertEquals($ast->fields[0]->defaultValue, $value);
    }
}
