<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Reflection;

use Hoa\Compiler\Llk\TreeNode;
use Serafim\Railgun\Compiler\Dictionary;
use Serafim\Railgun\Reflection\Common\Arguments;
use Serafim\Railgun\Reflection\Common\Directives;
use Serafim\Railgun\Reflection\Abstraction\FieldInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Common\HasName;

/**
 * Class Field
 * @package Serafim\Railgun\Reflection
 */
class Field extends Definition implements FieldInterface
{
    use HasName;
    use Arguments;
    use Directives;

    public function __construct(Document $document, TreeNode $ast)
    {
        parent::__construct($document, $ast);
    }

    public function getType(): TypeInterface
    {
        throw new \LogicException(__METHOD__ . ' not implemented yet');
    }
}
