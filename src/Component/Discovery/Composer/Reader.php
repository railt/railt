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

/**
 * Class Reader
 */
class Reader
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * Reader constructor.
     *
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * @return iterable|Package[]
     */
    public function getPackages(): iterable
    {
        $local = $this->composer->getRepositoryManager()->getLocalRepository();

        foreach ($local->getPackages() as $package) {
            yield new Package($this->composer, $package);
        }

        yield new Package($this->composer, $this->composer->getPackage());
    }
}
