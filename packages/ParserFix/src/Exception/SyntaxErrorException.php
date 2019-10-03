<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Exception;

use Phplrt\Position\Position;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class SyntaxErrorException
 */
class SyntaxErrorException extends \RuntimeException
{
    /**
     * SyntaxErrorException constructor.
     *
     * @param string $message
     * @param ReadableInterface $source
     * @param int $offset
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function __construct(string $message, ReadableInterface $source, int $offset)
    {
        parent::__construct($message);

        if ($source instanceof FileInterface) {
            $this->file = $source->getPathname();
            $this->line = Position::fromOffset($source, $offset)->getLine();
        }
    }
}
