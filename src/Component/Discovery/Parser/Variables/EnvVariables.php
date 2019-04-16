<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Discovery\Parser\Variables;

/**
 * Class EnvVariables
 */
class EnvVariables implements \IteratorAggregate
{
    /**
     * @var string
     */
    protected const ENV_VARIABLES_PREFIX = 'env.';

    /**
     * @var array
     */
    private $env;

    /**
     * ComposerVariables constructor.
     *
     * @param array $env
     */
    public function __construct(array $env)
    {
        $this->env = $env;
    }

    /**
     * @return \Traversable|string[]
     */
    public function getIterator(): \Traversable
    {
        foreach ($this->env as $name => $value) {
            if (! \is_string($value)) {
                continue;
            }

            yield self::ENV_VARIABLES_PREFIX . $name => $value;
        }
    }
}
