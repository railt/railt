<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery\Composer;

use Composer\Composer;
use Composer\Package\PackageInterface;
use Railt\Component\Discovery\Parser\Parser;
use Railt\Component\Discovery\Parser\ParserInterface;
use Railt\Component\Discovery\Parser\Variables\ComposerVariables;
use Railt\Component\Discovery\Parser\Variables\EnvVariables;
use Railt\Component\Discovery\Parser\Variables\RootPackageVariables;
use Railt\Component\Discovery\Parser\Variables\VendorPackageVariables;

/**
 * Class Package
 */
class Package
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var PackageInterface
     */
    private $package;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * Package constructor.
     *
     * @param Composer $composer
     * @param PackageInterface $package
     */
    public function __construct(Composer $composer, PackageInterface $package)
    {
        $this->composer = $composer;
        $this->package = $package;
        $this->parser = $this->bootParser();
    }

    /**
     * @return ParserInterface
     */
    private function bootParser(): ParserInterface
    {
        $parser = new Parser();

        $parser->defineAll(new ComposerVariables($this->composer));
        $parser->defineAll(new RootPackageVariables($this->composer));
        $parser->defineAll(new VendorPackageVariables($this->composer, $this->package));
        $parser->defineAll(new EnvVariables(\array_merge($_ENV ?? [], $_SERVER ?? [])));

        return $parser;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->package->getName();
    }

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface
    {
        return $this->parser;
    }

    /**
     * @param string $name
     * @return Section|null
     */
    public function getSection(string $name): ?Section
    {
        $extra = $this->package->getExtra();

        if (! isset($extra[$name])) {
            return null;
        }

        return new Section($this, $this->getParser(), $name, $extra[$name]);
    }

    /**
     * @return iterable|Section[]
     */
    public function getSections(): iterable
    {
        foreach ($this->package->getExtra() as $name => $value) {
            yield new Section($this, $this->getParser(), $name, $value);
        }
    }
}
