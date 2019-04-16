<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Dumper;

use Railt\Component\Dumper\Resolver\ResolverInterface;

/**
 * Interface TypeDumperInterface
 */
interface TypeDumperInterface
{
    /**
     * @param string|ResolverInterface $resolver
     * @return TypeDumperInterface
     */
    public function add(string $resolver): self;

    /**
     * @param mixed $value
     * @return string
     */
    public function type($value): string;

    /**
     * @param mixed $value
     * @return string
     */
    public function value($value): string;

    /**
     * @param mixed $value
     * @return string
     */
    public function dump($value): string;
}
