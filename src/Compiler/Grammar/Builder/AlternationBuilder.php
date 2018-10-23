<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Builder;

use Railt\Compiler\Parser\Rule\Alternation;

/**
 * Class AlternationBuilder
 */
class AlternationBuilder extends Alternation implements Buildable
{
    use Movable;
    use Renameable;
    use Repointable;
    use Instantiable;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->id, $this->children, $this->name];
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Alternation::class;
    }
}
