<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Runtime;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\Support\Identifier;
use Railt\Reflection\Builder\DocumentBuilder;
use Railt\Reflection\Contracts\Types\TypeInterface;

/**
 * Class AbstractTypeBuilder
 * @mixin TypeInterface
 */
trait TypeBuilder
{
    use Builder;

    /**
     * @var string|null
     */
    private $id;

    /**
     * AbstractTypeBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     */
    protected function bootTypeBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->bootBuilder($ast, $document);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getUniqueId(): string
    {
        if ($this->id === null) {
            $this->id = Identifier::generate();
        }

        return $this->id;
    }
}
