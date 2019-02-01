<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\SubProtocol;

use Railt\Foundation\ConnectionInterface;
use Railt\Http\Identifiable;

/**
 * Class BaseProtocol
 */
abstract class BaseProtocol implements ProtocolInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * Lifecycle constructor.
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->connection->getId();
    }

    /**
     * @param int $id
     * @return Identifiable
     */
    public function withId(int $id): Identifiable
    {
        $this->connection->withId($id);

        return $this;
    }
}
