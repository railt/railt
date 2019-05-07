<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Discovery;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var string
     */
    private const COMPOSER_PATH = __DIR__ . '/../../composer.json';

    /**
     * @var string[]|array[]
     */
    private const COMPOSER_EXTRA = [
        'scripts' => [
            'post-autoload-dump' => [
                'Railt\\Discovery\\Manifest::discover',
            ],
        ],
        'extra'   => [
            'discovery'            => [
                'allows-in-unit-tests',
            ],
            'discovery:except' => [
                'allows-in-unit-tests:hidden',
            ],
            'allows-in-unit-tests' => [
                'example' => 'valid-value',
                'hidden'  => [
                    'value',
                ],
            ],
            'hide-from-unit-tests' => [
                'example' => 'invalid-value',
            ],
        ],
    ];

    /**
     * @var array
     */
    private $original = [];

    /**
     * @param array $data
     * @return void
     */
    private function write(array $data): void
    {
        $json = \json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES);

        \file_put_contents(self::COMPOSER_PATH, $json);
    }

    /**
     * @return array
     */
    private function read(): array
    {
        return \json_decode(\file_get_contents(self::COMPOSER_PATH), true);
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->original = $this->read();

        $this->write(\array_merge_recursive($this->original, self::COMPOSER_EXTRA));

        \system(\sprintf('cd "%s" && composer du', __DIR__ . '/..'));
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->write($this->original);
    }
}
