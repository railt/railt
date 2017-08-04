<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Exceptions;

use Hoa\Compiler\Exception\UnrecognizedToken;
use Serafim\Railgun\Compiler\File;

/**
 * Class UnrecognizedTokenException
 * @package Serafim\Railgun\Exceptions
 */
class UnrecognizedTokenException extends \ParseError
{
    use RailgunException;

    /**
     * @param UnrecognizedToken|\Exception $parent
     * @param File $file
     * @return UnrecognizedTokenException
     */
    public static function fromHoa(UnrecognizedToken $parent, File $file): UnrecognizedTokenException
    {
        return static::new($parent->getMessage())
            ->from($parent)->in($file->getPathname());
    }
}
