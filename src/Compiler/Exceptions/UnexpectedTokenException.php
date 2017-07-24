<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Compiler\Exceptions;

use Hoa\Compiler\Exception\UnrecognizedToken;

/**
 * Class UnexpectedTokenException
 * @package Serafim\Railgun\Compiler\Exceptions
 */
class UnexpectedTokenException extends \ParseError
{
    /**
     * UnexpectedTokenException constructor.
     * @param UnrecognizedToken|\Hoa\Compiler\Exception\Exception|\Exception $parent
     * @param \SplFileInfo|null $info
     */
    public function __construct(UnrecognizedToken $parent, ?\SplFileInfo $info)
    {
        parent::__construct($parent->getMessage(), $parent->getCode());

        if ($info !== null) {
            $this->file = $info->getPathname();
        }

        $this->line = $parent->getLine();
    }
}
