<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Exception;

/**
 * Class GraphQLClientException
 */
class GraphQLClientException extends GraphQLException
{
    /**
     * GraphQLClientException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $prev
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $prev = null)
    {
        parent::__construct($message, $code, $prev);

        $this->publish();
    }
}
