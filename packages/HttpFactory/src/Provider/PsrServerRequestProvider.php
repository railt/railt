<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\HttpFactory\Provider;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PsrServerRequestProvider
 */
final class PsrServerRequestProvider extends PsrMessageProvider
{
    /**
     * PsrRequestProvider constructor.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        parent::__construct($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryArguments(): array
    {
        return $this->message->getQueryParams();
    }

    /**
     * {@inheritDoc}
     */
    public function getPostArguments(): array
    {
        return (array)$this->message->getParsedBody();
    }
}
