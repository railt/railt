<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use Railt\Io\DeclarationInterface;
use Railt\Io\Readable;

/**
 * Class DeclarationTestCase
 */
class DeclarationTestCase extends TestCase
{
    /**
     * @return string
     */
    protected function getPathname(): string
    {
        return __FILE__;
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     */
    public function testDeclarationClass(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        /** @var DeclarationInterface $declaration */
        $declaration = $readable->getDeclarationInfo();

        $this->assertSame(parent::class, $declaration->getClass());
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws \ReflectionException
     */
    public function testDeclarationFile(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        /** @var DeclarationInterface $declaration */
        $declaration = $readable->getDeclarationInfo();

        $ref = new \ReflectionClass(parent::class);

        $this->assertSame($ref->getFileName(), $declaration->getPathname());
    }

    /**
     * @dataProvider provider
     * @param \Closure $factory
     * @throws \ReflectionException
     */
    public function testDeclarationLine(\Closure $factory): void
    {
        /** @var Readable $readable */
        $readable = $factory();

        $provider = new \ReflectionMethod(parent::class, 'provider');

        /** @var DeclarationInterface $declaration */
        $declaration = $readable->getDeclarationInfo();

        $this->assertGreaterThan($provider->getStartLine(), $declaration->getLine());
        $this->assertLessThan($provider->getEndLine(), $declaration->getLine());
    }
}
