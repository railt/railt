<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Exceptions;

use Hoa\Compiler\Exception\UnrecognizedToken;
use Railt\Support\File;

/**
 * Class UnrecognizedTokenException
 * @package Railt\Exceptions
 */
class UnrecognizedTokenException extends \ParseError implements GraphQLSchemaException
{
    use RailtException;

    /**
     * @var int
     */
    protected $codeLine = 0;

    /**
     * @var int
     */
    protected $codeColumn = 0;

    /**
     * @param UnrecognizedToken|\Exception $parent
     * @param File $file
     * @return UnrecognizedTokenException
     */
    public static function fromHoa(UnrecognizedToken $parent, File $file): UnrecognizedTokenException
    {
        $self = static::new($parent->getMessage())
            ->in($file->getPathname(), $parent->getLine())
            ->from($parent);

        $self->codeLine = $parent->getLine();
        $self->codeColumn = $parent->getColumn();

        return $self;
    }

    /**
     * @return int
     */
    public function getCodeColumn(): int
    {
        return $this->codeColumn;
    }

    /**
     * @return int
     */
    public function getCodeLine(): int
    {
        return $this->codeLine;
    }
}
