<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Support;

use Railgun\Endpoint;

/**
 * Trait Constructors
 * @package Railgun\Support
 * @mixin Endpoint
 */
trait Constructors
{
    /**
     * @param \SplFileInfo $info
     * @return Endpoint
     * @throws \Railgun\Exceptions\SemanticException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\NotReadableException
     */
    public static function fromFileInfo(\SplFileInfo $info): Endpoint
    {
        return new static(File::physics($info));
    }

    /**
     * @param string $pathName
     * @return Endpoint
     * @throws \Railgun\Exceptions\SemanticException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\NotReadableException
     */
    public static function fromFilePath(string $pathName): Endpoint
    {
        return new static(File::path($pathName));
    }

    /**
     * @param string $sources
     * @return Endpoint
     * @throws \Railgun\Exceptions\SemanticException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\NotReadableException
     */
    public static function fromSources(string $sources): Endpoint
    {
        return new static(File::virual($sources));
    }

    /**
     * @param \SplFileInfo|string $schema
     * @return Endpoint
     * @throws \InvalidArgumentException
     * @throws \Railgun\Exceptions\CompilerException
     * @throws \Railgun\Exceptions\NotReadableException
     * @throws \Railgun\Exceptions\SemanticException
     */
    public static function new($schema): Endpoint
    {
        switch (true) {
            case $schema instanceof \SplFileInfo:
                return static::fromFileInfo($schema);
            case is_string($schema) && is_file($schema):
                return static::fromFilePath($schema);
            case is_string($schema):
                return static::fromSources($schema);
        }

        throw new \InvalidArgumentException('Schema argument must a valid file or source code text');
    }
}
