<?php

declare(strict_types=1);

namespace Railt\Extension\DefaultValue;

use Railt\EventDispatcher\EventDispatcherInterface;
use Railt\Foundation\Event\Resolve\FieldResolving;
use Railt\TypeSystem\Definition\Type\EnumType;
use Railt\TypeSystem\Definition\Type\ScalarType;
use Railt\TypeSystem\ListType;
use Railt\TypeSystem\NonNullType;
use Railt\TypeSystem\WrappingTypeInterface;

final class DefaultValueContext
{
    /**
     * @var \Closure(FieldResolving):void
     */
    private readonly \Closure $fieldResolving;

    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
    ) {
        $this->dispatcher->addListener(
            eventName: FieldResolving::class,
            listener: $this->fieldResolving = $this->tryFieldResolving(...),
        );
    }

    public function dispose(): void
    {
        $this->dispatcher->removeListener(FieldResolving::class, $this->fieldResolving);
    }

    private function tryFieldResolving(FieldResolving $event): void
    {
        //
        // In the case that the result has already been obtained,
        // then this logic will be ignored.
        //
        if ($event->hasResult()) {
            return;
        }

        $this->tryResolveFromParent($event);
    }

    private function tryResolveFromParent(FieldResolving $event): void
    {
        $name = $event->input->getFieldName();

        /** @psalm-suppress MixedAssignment */
        $parent = $event->input->getParentValue();

        //
        // In the case that the parent data is an array and there is a key that
        // matches the name of the requested field, then we return it.
        //
        // ```graphql
        //  type Collection {
        //    items: [Item]     # return [ ['id' => 1], ['id' => 2] ];
        //  }
        //
        //  type Item {
        //    id: ID            # $parent = ['id' => 1];
        //                      # ^^^^^^^ - Expected field is "id" and $parent
        //                      #           is array that contain 'id' key.
        //  }
        // ```
        //
        if (\is_array($parent) && \array_key_exists($name, $parent)) {
            $event->setResult($parent[$name]);

            return;
        }

        //
        // In the case that the parent data is an instance of \ArrayAccess and
        // there is an `offset` that contains the name of the requested field,
        // then we return it.
        //
        // ```graphql
        //  type Collection {
        //    items: [Item]     # return [
        //                      #     new \ArrayObject([ 'id' => 1 ]),
        //                      #     new \ArrayObject([ 'id' => 2 ]),
        //                      # ];
        //  }
        //
        //  type Item {
        //    id: ID            # $parent = new \ArrayObject(['id' => 1]);
        //                      # ^^^^^^^ - Expected field is "id" and $parent
        //                      #           is an instance of \ArrayAccess that
        //                      #           contain 'id' offset.
        //  }
        // ```
        //
        if ($parent instanceof \ArrayAccess && $parent->offsetExists($name)) {
            $event->setResult($parent->offsetGet($name));

            return;
        }

        //
        // In the case that the parent is an object that contains an identical
        // property to the requested field.
        //
        // ```php
        //  final readonly class Item
        //  {
        //      public function __construct(
        //          public int $id,
        //      ) {}
        //  }
        // ```
        //
        // ```graphql
        //  type Collection {
        //    items: [Item]     # return [
        //                      #     new Item(id: 1),
        //                      #     new Item(id: 2),
        //                      # ];
        //  }
        //
        //  type Item {
        //    id: ID            # $parent = new Item(id: 1);
        //                      # ^^^^^^^ - Expected field is "id" and $parent
        //                      #           is an instance that contain 'id'
        //                      #           property.
        //  }
        // ```
        //
        if (\is_object($parent) && \property_exists($parent, $name)) {
            $event->setResult($parent->$name);

            return;
        }

        //
        // In the case that the parent is an object with `__get` and `__isset`
        // methods, and `__isset` returns TRUE when passing the requested
        // field name.
        //
        // ```php
        //  final readonly class Item
        //  {
        //      public function __construct(
        //          private int $id,
        //      ) {}
        //
        //      public function __isset(string $name): bool
        //      {
        //          return $name === 'id';
        //      }
        //
        //      public function __get(string $name): mixed
        //      {
        //          return $name === 'id' ? $this->id : null;
        //      }
        //  }
        // ```
        //
        // ```graphql
        //  type Collection {
        //    items: [Item]     # return [
        //                      #     new Item(id: 1),
        //                      #     new Item(id: 2),
        //                      # ];
        //  }
        //
        //  type Item {
        //    id: ID            # $parent = new Item(id: 1);
        //                      # ^^^^^^^ - Expected field is "id" and $parent
        //                      #           is an instance that returns value
        //                      #           from "__get" method.
        //  }
        // ```
        //
        if (\is_object($parent)
            && \method_exists($parent, '__isset')
            && $parent->__isset($name)
            && \method_exists($parent, '__get')
        ) {
            $event->setResult($parent->__get($name));

            return;
        }

        $this->tryResolveFromType($event);
    }

    private function tryResolveFromType(FieldResolving $event): void
    {
        $field = $event->input->getFieldDefinition();

        $type = $field->getType();

        //
        // In the case that the field is non-null list.
        //
        if ($type instanceof ListType || (
                $type instanceof NonNullType && $type->getOfType() instanceof ListType
            )) {
            $event->setResult([]);

            return;
        }

        //
        // In the case that the field is nullable.
        //
        if (!$type instanceof NonNullType) {
            $event->setResult(null);

            return;
        }

        // Skip in case of "field" is not a wrapped type
        if (!$field instanceof WrappingTypeInterface) {
            return;
        }

        //
        // In the case that the field is NonNullType that
        // contain NOT ScalarType or EnumType (i.e. ListType, ObjectType,
        // InterfaceType, etc.), then return empty array.
        //
        $inner = $field->getOfType();

        if (!$inner instanceof ScalarType && !$inner instanceof EnumType) {
            $event->setResult([]);
        }
    }
}
