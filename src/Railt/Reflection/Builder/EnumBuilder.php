<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Reflection\Base\BaseEnum;
use Railt\Reflection\Builder\Enum\ValueBuilder;
use Railt\Reflection\Builder\Support\Builder;
use Railt\Reflection\Builder\Support\Compilable;

/**
 * Class EnumBuilder
 */
class EnumBuilder extends BaseEnum implements Compilable
{
    use Builder;

    /**
     * EnumBuilder constructor.
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @throws \Railt\Reflection\Exceptions\TypeConflictException
     */
    public function __construct(TreeNode $ast, DocumentBuilder $document)
    {
        $this->bootBuilder($ast, $document);
    }

    /**
     * @param TreeNode $ast
     * @return bool
     * @throws \LogicException
     */
    public function compile(TreeNode $ast): bool
    {
        if ($ast->getId() === '#Value') {
            $value =  new ValueBuilder($ast, $this->getDocument(), $this);
            $this->values[$value->getValue()] = $value;

            return true;
        }

        return false;
    }
}
