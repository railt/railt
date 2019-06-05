<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Http;

use PHPUnit\Framework\ExpectationFailedException;
use Railt\Http\Extension\Extension;
use Railt\Http\Extension\MutableExtensionProviderInterface;
use Railt\Http\Extension\MutableExtensionProviderTrait;

/**
 * Class ExtensionsProviderTestCase
 */
class ExtensionsProviderTestCase extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOriginalExtensionsUsingObjects(): void
    {
        $extensions = [
            new Extension('a', 42),
            new Extension('b', 23),
        ];

        $provider = $this->provider($extensions);

        $this->assertSame($extensions, $provider->getOriginalExtensions());
    }

    /**
     * @param array $extensions
     * @return MutableExtensionProviderInterface
     */
    protected function provider(array $extensions = []): MutableExtensionProviderInterface
    {
        return new class($extensions) implements MutableExtensionProviderInterface {
            use MutableExtensionProviderTrait;

            public function __construct(array $extensions)
            {
                foreach ($extensions as $key => $value) {
                    $this->withExtension($key, $value);
                }
            }
        };
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testOriginalExtensionsUsingArrays(): void
    {
        $extensions = [
            'a' => 42,
            'b' => 23,
        ];

        $provider = $this->provider($extensions);

        [$first, $second] = $provider->getOriginalExtensions();

        $this->assertSame('a', $first->getName());
        $this->assertSame(42, $first->getValue());

        $this->assertSame('b', $second->getName());
        $this->assertSame(23, $second->getValue());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testExtensionsUniqueKeys(): void
    {
        $provider = $this->provider([
            new Extension('a', 23),
            new Extension('a', 42),
        ]);

        $this->assertCount(1, $provider->getExtensions());
        $this->assertCount(1, $provider->getOriginalExtensions());

        $this->assertArrayHasKey('a', $provider->getExtensions());
        $this->assertSame(42, $provider->getExtensions()['a']);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRenderableExtensions(): void
    {
        $provider = $this->provider([
            new Extension('a', 23),
            new Extension('b', 42),
        ]);

        $this->assertSame(['a' => 23, 'b' => 42], $provider->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRemovingExtension(): void
    {
        $provider = $this
            ->provider([
                new Extension('a', 23),
                new Extension('b', 42),
            ])
            ->withoutExtension('a');

        $this->assertSame(['b' => 42], $provider->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRewritingExtension(): void
    {
        $provider = $this
            ->provider([
                new Extension('a', 23),
                new Extension('b', 42),
            ])
            ->setExtensions([
                new Extension('c', 23),
                new Extension('d', 42),
            ]);

        $this->assertSame(['c' => 23, 'd' => 42], $provider->getExtensions());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     */
    public function testRewritingExtensionUsingArrays(): void
    {
        $provider = $this
            ->provider([
                new Extension('a', 23),
                new Extension('b', 42),
            ])
            ->setExtensions([
                'c' => 23,
                'd' => 42,
            ]);

        $this->assertSame(['c' => 23, 'd' => 42], $provider->getExtensions());
    }

    /**
     * @return void
     */
    public function testInvalidExtension(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp(
            '/^First argument should be a name of extension or extension ' .
            'instance, but object\(stdClass\#\d+\) given$/'
        );

        $provider = $this->provider();
        $provider->withExtension(new \stdClass());
    }
}
