<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Parser\Rule;

/**
 * Class BaseProduction
 */
abstract class BaseProduction extends BaseSymbol implements Production
{
    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var array|string[]|int[]
     */
    protected $children;

    /**
     * BaseProduction constructor.
     * @param string|int $id
     * @param array|string[]|int[] $children
     * @param string|null $name
     */
    public function __construct($id, array $children = [], string $name = null)
    {
        $this->children = $children;
        $this->name     = $name;

        parent::__construct($id, \is_string($name));
    }

    /**
     * @return array
     */
    public function then(): array
    {
        return $this->children;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
