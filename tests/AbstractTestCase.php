<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Tests;

use PHPUnit\Framework\TestCase;
use Railgun\Support\File;

/**
 * Class AbstractTestCase
 * @package Railgun\Tests
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
     */
    public function file(string $file): File
    {
        return File::path($this->resource($file));
    }
}
