<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery\Parser\Variables;

use Composer\Composer;

/**
 * Class ComposerVariables
 */
class ComposerVariables implements \IteratorAggregate
{
    /**
     * @var string
     */
    protected const COMPOSER_VARIABLES_PREFIX = 'composer.';

    /**
     * @var Composer
     */
    private Composer $composer;

    /**
     * ComposerVariables constructor.
     *
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
    }

    /**
     * @return \Traversable|string[]
     * @throws \RuntimeException
     */
    public function getIterator(): \Traversable
    {
        $config = $this->composer->getConfig()->all();

        foreach ((array)($config['config'] ?? []) as $key => $value) {
            if (! \is_string($value)) {
                continue;
            }

            yield self::COMPOSER_VARIABLES_PREFIX . $key => $value;
        }
    }
}
