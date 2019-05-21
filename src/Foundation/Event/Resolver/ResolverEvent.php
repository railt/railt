<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Resolver;

use Railt\Json\Json;
use Railt\Http\Identifiable;
use Railt\Http\InputInterface;
use Railt\Http\RequestInterface;
use Symfony\Component\EventDispatcher\Event;
use Railt\SDL\Contracts\Dependent\FieldDefinition;
use Railt\SDL\Contracts\Definitions\TypeDefinition;

/**
 * Class ResolverEvent
 */
abstract class ResolverEvent extends Event implements ResolverEventInterface
{
    /**
     * @var InputInterface|null
     */
    private $input;

    /**
     * @var \Closure|null
     */
    private $inputResolver;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var bool
     */
    private $hasResult = false;

    /**
     * @var mixed
     */
    private $parentResult;

    /**
     * @var Identifiable
     */
    private $connection;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FieldDefinition
     */
    private $field;

    /**
     * ResolverEvent constructor.
     *
     * @param Identifiable $connection
     * @param RequestInterface $request
     * @param FieldDefinition $field
     */
    public function __construct(Identifiable $connection, RequestInterface $request, FieldDefinition $field)
    {
        $this->connection = $connection;
        $this->request = $request;
        $this->field = $field;
    }

    /**
     * @return FieldDefinition
     */
    public function getFieldDefinition(): FieldDefinition
    {
        return $this->field;
    }

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->field->getParent();
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return [
            'connection' => $this->getConnection()->getId(),
            'request'    => $this->getRequest()->getId(),
            'field'      => (string)$this->field,
            'type'       => (string)$this->field->getParent(),
            'input'      => $this->getInput(),
            'result'     => $this->result,
        ];
    }

    /**
     * @return Identifiable
     */
    public function getConnection(): Identifiable
    {
        return $this->connection;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        if (! $this->input) {
            \assert($this->inputResolver instanceof \Closure);

            $this->input = ($this->inputResolver)($this);
        }

        return $this->input;
    }

    /**
     * @param InputInterface $input
     * @return ResolverEvent|$this
     */
    public function withInput(InputInterface $input): self
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param \Closure $resolver
     * @return ResolverEvent|$this
     */
    public function withInputResolver(\Closure $resolver): self
    {
        [$this->inputResolver, $this->input] = [$resolver, null];

        return $this;
    }

    /**
     * @param mixed|null $value
     * @return ResolverEvent|$this
     */
    public function withResult($value = null): self
    {
        [$this->result, $this->hasResult] = [$value, true];

        return $this;
    }

    /**
     * @return bool
     */
    public function hasResult(): bool
    {
        return $this->hasResult;
    }

    /**
     * @return mixed
     */
    public function getParentResult()
    {
        return $this->parentResult;
    }

    /**
     * @param mixed|null $value
     * @return ResolverEvent|$this
     */
    public function withParentResult($value = null): self
    {
        $this->parentResult = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->getInput()->getPath();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return Json::encode($this->getResult());
        } catch (\JsonException $e) {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
