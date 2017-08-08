<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Runtime;

use Serafim\Railgun\Compiler\File;

/**
 * Class EndpointConstructors
 * @package Serafim\Railgun\Runtime
 */
trait EndpointConstructors
{
    /**
     * @param \SplFileInfo $info
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function fromFile(\SplFileInfo $info): Endpoint
    {
        return new static(File::physics($info));
    }

    /**
     * @param string $pathName
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     * @throws \Serafim\Railgun\Exceptions\NotReadableException
     */
    public static function fromFilePath(string $pathName): Endpoint
    {
        return new static(File::path($pathName));
    }

    /**
     * @param string $sources
     * @return Endpoint
     * @throws \Serafim\Railgun\Exceptions\SemanticException
     * @throws \Serafim\Railgun\Exceptions\CompilerException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    public static function fromSources(string $sources): Endpoint
    {
        return new static(File::virual($sources));
    }
}
