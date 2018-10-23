<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Io;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Io\File;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return array
     */
    public function provider(): array
    {
        return [
            'File from sources'        => [function () {
                return File::fromSources($this->getSources(), $this->getPathname());
            }],
            'Sources'        => [function () {
                return File::fromSources($this->getSources());
            }],
            'File'       => [function () {
                return File::fromPathname($this->getPathname());
            }],
            'Clone file from sources'        => [function () {
                return File::fromReadable(File::fromSources($this->getSources(), $this->getPathname()));
            }],
            'Clone sources'        => [function () {
                return File::fromReadable(File::fromSources($this->getSources()));
            }],
            'Clone file'       => [function () {
                return File::fromReadable(File::fromPathname($this->getPathname()));
            }],
            'SplFileInfo'    => [function () {
                return File::fromSplFileInfo(new \SplFileInfo($this->getPathname()));
            }],
            'SplFileObject'  => [function () {
                return File::fromSplFileInfo(new \SplFileObject($this->getPathname()));
            }],
        ];
    }

    /**
     * @return string
     */
    protected function getSources(): string
    {
        return \file_get_contents($this->getPathname());
    }

    /**
     * @return string
     */
    abstract protected function getPathname(): string;
}
