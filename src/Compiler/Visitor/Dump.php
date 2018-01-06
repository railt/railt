<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Visitor;

use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;
use Railt\Compiler\TreeNode;

/**
 * Class \Railt\Compiler\Visitor\Dump.
 *
 * Dump AST produced by LL(k) compiler.
 *
 * @copyright Copyright © 2007-2017 Hoa community
 * @license New BSD License
 */
class Dump implements Visit
{
    /**
     * Indentation depth.
     *
     * @var int
     */
    protected static $i = 0;

    /**
     * Visit an element.
     *
     * @param Element|TreeNode $element Element to visit.
     * @param mixed &$handle Handle (reference).
     * @param mixed $eldnah Handle (not reference).
     * @return  mixed
     */
    public function visit(
        Element $element,
        &$handle = null,
        $eldnah = null
    ) {
        ++self::$i;

        $out = \str_repeat('>  ', self::$i) . $element->getId();

        if (null !== $value = $element->getValue()) {
            $out .=
                '(' .
                ('default' !== $value['namespace']
                    ? $value['namespace'] . ':'
                    : '') .
                $value['token'] . ', ' .
                $value['value'] . ')';
        }

        $data = $element->getData();

        if (! empty($data)) {
            $out .= ' ' . $this->dumpData($data);
        }

        $out .= "\n";

        /** @var TreeNode $child */
        foreach ($element->getChildren() as $child) {
            $out .= $child->accept($this, $handle, $eldnah);
        }

        --self::$i;

        return $out;
    }

    /**
     * Dump data.
     *
     * @param mixed $data Data.
     * @return  string
     */
    protected function dumpData($data)
    {
        $out = null;

        if (! \is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            $out .= '[' . $key . ' => ' . $this->dumpData($value) . ']';
        }

        return $out;
    }

    /**
     * @param TreeNode $ast
     * @return string
     */
    public function dump(TreeNode $ast): string
    {
        $result = (string)$this->visit($ast);

        $result = \str_replace('>  ', '    ', $result);
        $result = \preg_replace('/^\s{4}/ium', '', $result);

        $result = \preg_replace_callback('/token\((\w+),(.*?)\)/isu', function ($args) {
            return 'token(' . $args[1] . ',' . \str_replace(["\n"], ['\n'], $args[2]) . ')';
        }, $result);

        return \trim($result);
    }
}
