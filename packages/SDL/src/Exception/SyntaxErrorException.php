<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Position\Position;
use Phplrt\Position\PositionInterface;
use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Contracts\Source\ReadableInterface;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class SyntaxErrorException
 */
class SyntaxErrorException extends \RuntimeException
{
    /**
     * @var PositionInterface
     */
    private PositionInterface $position;

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

        $this->position = Position::fromOffset($source, $offset);

        if ($source instanceof FileInterface) {
            $this->file = $source->getPathname();
            $this->line = $this->position->getLine();
        }
    }

    /**
     * @return PositionInterface
     */
    public function getPosition(): PositionInterface
    {
        return $this->position;
    }
}
