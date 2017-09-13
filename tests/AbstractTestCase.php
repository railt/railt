<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests;

use Railt\Parser\File;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTestCase
 * @package Railt\Tests
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $resourcesPath = '';

    /**
     * @param string $file
     * @return string
     */
    public function resource(string $file): string
    {
        return __DIR__ . '/.resources/' . $this->resourcesPath . $file;
    }

    /**
     * @param string $file
     * @return File
     * @throws \Railt\Parser\Exceptions\NotReadableException
     */
    public function file(string $file): File
    {
        return File::path($this->resource($file));
    }
}
