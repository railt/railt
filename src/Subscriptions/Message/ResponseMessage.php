<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Message;

use Railt\Http\ResponseInterface;

/**
 * Class ResponseMessage
 */
class ResponseMessage extends Message
{
    /**
     * @var string
     */
    public const FIELD_PAYLOAD = 'payload';

    /**
     * ResponseMessage constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct([
            static::FIELD_ID      => $response->getId(),
            static::FIELD_TYPE    => static::TYPE_ANSWER,
            static::FIELD_PAYLOAD => $response->toArray(),
        ]);
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->attributes[static::FIELD_PAYLOAD] ?? [];
    }
}
