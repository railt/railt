<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests;

use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;

/**
 * Class TypeOverridesTestCase
 */
class TypeOverridesTestCase extends TestCase
{
    /**
     * @return array
     */
    public function typeMixesDataProvider(): array
    {
        $result = [];

        foreach ($this->typesDataProvider() as $name => [$source]) {
            foreach ($this->typesDataProvider() as $withName => [$withSource]) {
                $result[\sprintf('%s + %s', $name, $withName)] = [
                    \vsprintf('%s %s', [
                        $source,
                        $withSource,
                    ]),
                ];
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function typesDataProvider(): array
    {
        return [
            'enum'      => ['enum Example'],
            'input'     => ['input Example'],
            'interface' => ['interface Example'],
            'object'    => ['type Example'],
            'scalar'    => ['scalar Example'],
            'union'     => ['union Example'],
        ];
    }

    /**
     * @dataProvider typeMixesDataProvider
     *
     * @param string $source
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function testTypeRedefineByAnotherType(string $source): void
    {
        $this->expectExceptionMessage('There can be only one type named Example');

        $this->compile($source);
    }

    /**
     * @dataProvider typesDataProvider
     *
     * @param string $source
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function testTypeRedefineByDirective(string $source): void
    {
        $this->expectNotToPerformAssertions();

        $this->compile($source . ' directive @Example on FIELD');
    }

    /**
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    public function testDirectiveRedefineByDirective(): void
    {
        $this->expectExceptionMessage('There can be only one directive named @Example');

        $this->compile(\str_repeat('directive @Example on FIELD ', 2));
    }
}
