<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\Route;

/**
 * Class Relation
 */
class Relation
{
    public const PARENT_DEFAULT_FIELD = 'id';

    /**
     * @var string
     */
    private $child;

    /**
     * @var string
     */
    private $parent;

    /**
     * Relation constructor.
     * @param string $child
     * @param string $parent
     */
    public function __construct(string $child, string $parent = self::PARENT_DEFAULT_FIELD)
    {
        $this->child = $child;
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getChildFieldName(): string
    {
        return $this->child;
    }

    /**
     * @return string
     */
    public function getParentFieldName(): string
    {
        return $this->parent;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'parent' => $this->parent,
            'child'  => $this->child,
        ];
    }
}
