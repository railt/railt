<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Creators;

use Serafim\Railgun\Support\NameableInterface;

/**
 * Trait ProvidesNameableDefinition
 * @package Serafim\Railgun\Schema\Creators
 */
trait ProvidesNameableDefinition
{
    /**
     * @param NameableInterface $target
     * @param string $name
     * @param string $description
     * @return NameableInterface
     */
    private function applyNameable(NameableInterface $target, string $name, string $description): NameableInterface
    {
        $invocation = function(string $name, string $description) {
            $this->rename($name)->about($description);
        };

        $invocation->call($target, $name, $description);

        return $target;
    }

    /**
     * @param string|null $as
     * @return $this
     */
    public function named(?string $as)
    {
        $this->rename($as);

        return $this;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function means(?string $description)
    {
        $this->about($description);

        return $this;
    }
}
