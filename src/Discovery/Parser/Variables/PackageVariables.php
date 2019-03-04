<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Parser\Variables;

use Composer\Package\PackageInterface;

/**
 * Class Variables
 */
abstract class PackageVariables implements \IteratorAggregate
{
    /**
     * @var PackageInterface
     */
    protected $package;

    /**
     * PackageVariables constructor.
     *
     * @param PackageInterface $package
     */
    public function __construct(PackageInterface $package)
    {
        $this->package = $package;
    }

    /**
     * @return \Traversable|mixed[]
     */
    public function getIterator(): \Traversable
    {
        $prefix = \trim($this->prefix(), '.') . '.';

        foreach ($this->all() as $key => $value) {
            yield \trim($prefix . $key, '.') => $value;
        }
    }

    /**
     * @return string
     */
    abstract protected function prefix(): string;

    /**
     * @return iterable
     */
    protected function all(): iterable
    {
        yield '' => $this->getDirectory();

        //
        // Define package path variables.
        //
        yield 'dir' => $this->getDirectory();
        yield 'json' => $this->getDirectory() . '/composer.json';

        //
        // Package name
        //
        yield 'name' => $this->package->getName();
        yield 'name.pretty' => $this->package->getPrettyName();
        yield 'name.unique' => $this->package->getUniqueName();

        //
        // Package info
        //
        yield 'id' => $this->package->getId();
        yield 'type' => $this->package->getType();
        yield 'stability' => $this->package->getStability();
        yield 'date' => function () {
            $date = $this->package->getReleaseDate();

            return $date ? $date->format(\DateTime::RFC3339) : null;
        };

        //
        // Package installation info
        //
        yield 'installation' => $this->package->getTargetDir();
        yield 'installation.dir' => $this->package->getTargetDir();
        yield 'installation.type' => $this->package->getInstallationSource();

        //
        // Package notification info
        //
        yield 'notification' => $this->package->getNotificationUrl();
        yield 'notification.url' => $this->package->getNotificationUrl();

        //
        // Package version
        //
        yield 'version' => $this->package->getVersion();
        yield 'version.full' => $this->package->getFullPrettyVersion();
        yield 'version.pretty' => $this->package->getPrettyVersion();

        //
        // Package source info
        //
        yield 'source' => $this->package->getSourceUrl();
        yield 'source.url' => $this->package->getSourceUrl();
        yield 'source.type' => $this->package->getSourceType();
        yield 'source.ref' => $this->package->getSourceReference();

        //
        // Package dist info
        //
        yield 'dist' => $this->package->getDistUrl();
        yield 'dist.url' => $this->package->getDistUrl();
        yield 'dist.type' => $this->package->getDistType();
        yield 'dist.ref' => $this->package->getDistReference();
        yield 'dist.hash' => $this->package->getDistSha1Checksum();
    }

    /**
     * @return string
     */
    abstract protected function getDirectory(): string;
}
