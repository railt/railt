<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser\Generator;

use Twig\TwigFilter;
use Twig\TwigFunction;
use PackageVersions\Versions;
use Twig\Extension\AbstractExtension;

/**
 * Class VersionExtension
 */
class VersionExtension extends AbstractExtension
{
    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('version', [$this, 'version']),
            new TwigFunction('commit', [$this, 'commit']),
        ];
    }

    /**
     * @return array|TwigFilter[]
     * @psalm-suppress InvalidArgument
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('version', [$this, 'version']),
            new TwigFilter('commit', [$this, 'commit']),
        ];
    }

    /**
     * @param string $package
     * @return string
     * @throws \OutOfBoundsException
     */
    public function version(string $package): string
    {
        $chunks = $this->fullVersion($package);

        return \reset($chunks);
    }

    /**
     * @param string $package
     * @return array|string[]
     * @throws \OutOfBoundsException
     */
    private function fullVersion(string $package): array
    {
        return \explode('@', Versions::getVersion($package));
    }

    /**
     * @param string $package
     * @return string
     * @throws \OutOfBoundsException
     */
    public function commit(string $package): string
    {
        $chunks = $this->fullVersion($package);

        return \end($chunks);
    }
}
