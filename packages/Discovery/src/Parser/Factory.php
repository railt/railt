<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Parser;

use Composer\Composer;
use Composer\Package\PackageInterface;
use Railt\Discovery\Parser\Variables\ComposerVariables;
use Railt\Discovery\Parser\Variables\EnvVariables;
use Railt\Discovery\Parser\Variables\RootPackageVariables;
use Railt\Discovery\Parser\Variables\VendorPackageVariables;

/**
 * Class Factory
 */
class Factory
{
    /**
     * @param Composer $composer
     * @param PackageInterface $package
     * @return ParserInterface
     */
    public static function create(Composer $composer, PackageInterface $package): ParserInterface
    {
        $parser = new Parser();

        $parser->defineAll(new ComposerVariables($composer));
        $parser->defineAll(new RootPackageVariables($composer));
        $parser->defineAll(new VendorPackageVariables($composer, $package));
        $parser->defineAll(new EnvVariables(\array_merge($_ENV ?? [], $_SERVER ?? [])));

        return $parser;
    }
}
