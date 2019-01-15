<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Foundation\Responses;

use Railt\Foundation\Event\Resolver\FieldResolve;
use Railt\Foundation\Event\Resolver\TypeResolve;
use Railt\Http\Request;
use Railt\Http\ResponseInterface;
use Railt\Tests\Foundation\Stub\TraversableObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class InterfaceResponsesTestCase
 */
class InterfaceResponsesTestCase extends ResponsesTestCase
{
    /**
     * @return array
     */
    public function interfaceDataProvider(): array
    {
        return [
            'Interface A'       => ['A', 'a', '{ interface { ... on A { a: value } } }'],
            'Interface A (+ B)' => ['A', 'a', '{ interface { ... on A { a: value } ... on B { b: value } } }'],
            'Interface B'       => ['B', 'b', '{ interface { ... on B { b: value } } }'],
            'Interface (A +) B' => ['B', 'b', '{ interface { ... on A { a: value } ... on B { b: value } } }'],
        ];
    }

    /**
     * @dataProvider interfaceDataProvider
     * @param string $type
     * @param string $field
     * @param string $query
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testSingularResolving(string $type, string $field, string $query): void
    {
        $response = $this->interface($query, function (EventDispatcherInterface $events) use ($type) {
            $events->addListener(TypeResolve::class, function (TypeResolve $t) use ($type): void {
                $t->withResult($type);
            });

            return ['value' => 42];
        });

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['interface' => [$field => '42']], $response->getData());
    }

    /**
     * @dataProvider interfaceDataProvider
     * @param string $type
     * @param string $field
     * @param string $query
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testSingularFromArray(string $type, string $field, string $query): void
    {
        $response = $this->interface($query, function () use ($type) {
            return ['value' => 42, '__typename' => $type];
        });

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['interface' => [$field => '42']], $response->getData());
    }

    /**
     * @dataProvider interfaceDataProvider
     * @param string $type
     * @param string $field
     * @param string $query
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testSingularResolvingPriorityFromArray(string $type, string $field, string $query): void
    {
        $response = $this->interface($query, function (EventDispatcherInterface $events) use ($type) {
            $events->addListener(TypeResolve::class, function (TypeResolve $t) use ($type): void {
                $t->withResult($type);
            });

            return ['value' => 42, '__typename' => 'InvalidType'];
        });

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['interface' => [$field => '42']], $response->getData());
    }

    /**
     * @dataProvider interfaceDataProvider
     * @param string $type
     * @param string $field
     * @param string $query
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function testSingularResolvingFromTraversable(string $type, string $field, string $query): void
    {
        $response = $this->interface($query, function () use ($type) {
            return new TraversableObject(['value' => 42, '__typename' => $type]);
        });

        $this->assertSame([], $response->getErrors());
        $this->assertSame(['interface' => [$field => '42']], $response->getData());
    }

    /**
     * @param string $query
     * @param string $field
     * @param \Closure $then
     * @return \Railt\Http\ResponseInterface
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    protected function interface(string $query, \Closure $then, string $field = 'interface'): ResponseInterface
    {
        return $this->connection(function (EventDispatcherInterface $events) use ($field, $then): void {
            $resolver = function (FieldResolve $f) use ($events, $field, $then): void {
                if ($f->getPath() === $field) {
                    $f->withResult($then($events));
                }
            };

            $events->addListener(FieldResolve::class, $resolver);
        })->request(new Request($query));
    }

    /**
     * @return string
     */
    protected function getSchema(): string
    {
        return '
            schema {
                query: Query
            }
            
            type Query {
                interface: Interface
                interfaces: [Interface]
                a: A
                b: B
            }
            
            interface Interface {
                value: String
            }
            
            type A implements Interface { value: String }
            type B implements Interface { value: String }
        ';
    }
}
