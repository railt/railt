<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Message;

use Illuminate\Support\Fluent;
use Railt\Http\Identifiable;

/**
 * Class Message
 */
class Message extends Fluent implements MessageInterface
{
    /**
     * @var string
     */
    protected const FIELD_ID = 'id';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * Message constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * @param string $type
     * @return bool
     */
    public function is(string $type): bool
    {
        try {
            return $this->getType() === $type;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @param int $id
     * @return Identifiable
     */
    public function withId(int $id): Identifiable
    {
        $this->attributes[static::FIELD_ID] = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        if (isset($this->attributes[static::FIELD_ID])) {
            return (int)$this->attributes[static::FIELD_ID];
        }

        return 0;
    }

    /**
     * @return bool
     */
    public function hasId(): bool
    {
        return isset($this->attributes[static::FIELD_ID]);
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getType(): string
    {
        if (! isset($this->attributes[static::FIELD_TYPE])) {
            throw new \InvalidArgumentException('Message should provide "type" attribute');
        }

        return (string)$this->attributes[static::FIELD_TYPE];
    }
}
