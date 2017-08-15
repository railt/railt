<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Reflection\Common;

use Hoa\Compiler\Llk\TreeNode;
use Railgun\Reflection\Abstraction\DocumentTypeInterface;

/**
 * Trait UniqueId
 * @package Railgun\Reflection\Common
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
     * @param DocumentTypeInterface $document
     * @param TreeNode $ast
     */
    public function bootUniqueId(DocumentTypeInterface $document, TreeNode $ast): void
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
