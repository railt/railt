<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Request\QueryTypeTrait;
use Railt\Http\Request\MutableVariablesTrait;
use Railt\Http\Request\MutableOperationNameTrait;

/**
 * Class Request
 */
class Request implements MutableRequestInterface
{
    use QueryTypeTrait;
    use RenderableTrait;
    use MutableVariablesTrait;
    use MutableOperationNameTrait;

    /**
     * @var string
     */
    private $query;

    /**
     * Request constructor.
     *
     * @param string $query
     * @param array $variables
     * @param string|null $operationName
     */
    public function __construct(string $query, array $variables = [], string $operationName = null)
    {
        $this->variables = $variables;

        $this->withQuery($query);
        $this->renameOperation($operationName);
    }

    /**
     * @param string $query
     * @return MutableRequestInterface|$this
     */
    public function withQuery(string $query): MutableRequestInterface
    {
        $this->query = \trim($query);
        $this->type = $this->resolveType($this->query);

        return $this;
    }

    /**
     * @param string $query
     * @return string
     */
    private function resolveType(string $query): string
    {
        switch (true) {
            case \stripos($query, self::TYPE_MUTATION, 0) === 0:
                return self::TYPE_MUTATION;

            case \stripos($query, self::TYPE_SUBSCRIPTION, 0) === 0:
                return self::TYPE_SUBSCRIPTION;

            default:
                return self::TYPE_QUERY;
        }
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return \trim($this->query) === '';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::FIELD_QUERY          => $this->getQuery(),
            self::FIELD_VARIABLES      => $this->getVariables(),
            self::FIELD_OPERATION_NAME => $this->getOperationName(),
        ];
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}
