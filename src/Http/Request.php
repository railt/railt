<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\HasOperation;
use Railt\Http\Request\HasQueryType;
use Railt\Http\Request\HasVariables;

/**
 * Class Request
 */
class Request implements RequestInterface
{
    use HasVariables;
    use HasQueryType;
    use HasOperation;
    use HasIdentifier;

    /**
     * @var string
     */
    private $query;

    /**
     * Request constructor.
     *
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     */
    public function __construct(string $query, array $variables = [], string $operation = null)
    {
        $this->withQuery($query);
        $this->withVariables($variables);
        $this->withOperation($operation);
    }

    /**
     * @param string $query
     * @return RequestInterface|$this
     */
    public function withQuery(string $query): RequestInterface
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @param array $variables
     * @param string|null $operation
     * @return Request
     */
    public static function create(string $query, array $variables = [], string $operation = null): self
    {
        return new static($query, $variables, $operation);
    }
}
