<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL;

use Railt\Io\File;
use Railt\Reflection\Contracts\Definitions\Directive\Location;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Reflection\CompilerInterface;

/**
 * Class DirectiveLocationsTestCase
 */
class DirectiveLocationsTestCase extends AbstractSDLTestCase
{
    /**
     * @dataProvider dateCompilersProvider
     *
     * @param CompilerInterface $compiler
     * @return void
     */
    public function testLocations(CompilerInterface $compiler): void
    {
        foreach (Location::TARGET_GRAPHQL_SDL as $definition) {
            foreach (Location::TARGET_GRAPHQL_SDL as $implementation) {
                $isPositiveTest = $definition === $implementation;

                $source = File::fromSources(
                    'directive @test on ' . $definition . "\n" .
                    $this->getBody($implementation)
                );

                try {
                    $compiler->compile($source);

                    $error = ($isPositiveTest ? 'Must be positive:' : 'Must be negative:')  .
                        "\n" . $source->getContents() .
                        "\n" . \str_repeat('-', 80);
                    $this->assertTrue($isPositiveTest, $error);

                } catch (\Throwable $e) {

                    $error = ($isPositiveTest ? 'Must be positive:' : 'Must be negative:') .
                        "\n" . $source->getContents() .
                        "\n" . \str_repeat('-', 80);
                    $this->assertFalse($isPositiveTest, $error);
                }
            }
        }
    }

    /**
     * @param string $location
     * @return string
     */
    private function getBody(string $location): string
    {
        switch ($location) {
            case Location::TARGET_SCHEMA:
                return 'type T {} schema @test { query: T }';

            case Location::TARGET_OBJECT:
                return 'type T @test {}';

            case Location::TARGET_INPUT_OBJECT:
                return 'input T @test {}';

            case Location::TARGET_INPUT_FIELD_DEFINITION:
                return 'input T { field: String @test }';

            case Location::TARGET_ENUM:
                return 'enum T @test { Value }';

            case Location::TARGET_ENUM_VALUE:
                return 'enum T { Value @test }';

            case Location::TARGET_UNION:
                return 'union T = String | Int @test';

            case Location::TARGET_INTERFACE:
                return 'interface T @test {}';

            case Location::TARGET_SCALAR:
                return 'scalar T @test';

            case Location::TARGET_FIELD_DEFINITION:
                return 'type T { field: String @test }';

            case Location::TARGET_ARGUMENT_DEFINITION:
                return 'type T { field(arg: String @test): String }';
        }

        throw new \InvalidArgumentException('Invalid location ' . $location);
    }
}
