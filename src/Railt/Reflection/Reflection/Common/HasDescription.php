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
use Railt\Reflection\Contracts\NamedDefinitionInterface;

/**
 * Trait HasDescription
 * @package Railt\Reflection\Reflection\Common
 */
trait HasDescription
{
    /**
     * @var string
     */
    protected $description = '';

    /**
     * @param DocumentInterface $document
     * @param TreeNode $ast
     */
    public function bootHasDescription(DocumentInterface $document, TreeNode $ast): void
    {
        /** @var TreeNode $child */
        foreach ($ast->getChildren() as $child) {
            if ($child->getId() === '#Description') {
                /** @var TreeNode $value */
                foreach ($child->getChildren() as $value) {
                    $this->description .= $this->escapeComments($value->getValueValue());
                }
            }
        }
    }

    /**
     * @param string $description
     * @return string
     */
    private function escapeComments(string $description): string
    {
        return preg_replace('/\s*#/imu', "\n", $description);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return (string)$this->description;
    }
}
