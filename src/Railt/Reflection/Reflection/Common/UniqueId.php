<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Contracts\DocumentInterface;

/**
 * Trait UniqueId
 * @package Railt\Reflection\Reflection\Common
 */
trait UniqueId
{
    /**
     * @var int
     */
    protected static $lastId = 0;

    /**
     * @var int
     */
    private $id;

    /**
     * @param DocumentInterface $document
     * @param TreeNode $ast
     */
    public function bootUniqueId(DocumentInterface $document, TreeNode $ast): void
    {
        $this->id = ++self::$lastId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
