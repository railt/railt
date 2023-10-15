<?php

declare(strict_types=1);

namespace Railt\Executor\Webonyx\Http;

use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\InlineFragmentNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Contracts\Http\InputInterface;
use Railt\Contracts\Http\RequestInterface;
use Railt\TypeSystem\Definition\FieldDefinition;

/**
 * @template-implements InputInterface<FieldDefinition>
 */
final class WebonyxInput implements InputInterface
{
    /**
     * @var array|null
     */
    private ?array $expectedTypes = null;

    /**
     * @var array<int<0, max>, array>
     */
    private array $selection = [];

    /**
     * @param array<non-empty-string, mixed> $arguments
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly FieldDefinition $field,
        private readonly ResolveInfo $info,
        private readonly mixed $parent = null,
        private readonly array $arguments = [],
    ) {
    }

    public function hasArgument(string $name): bool
    {
        return \array_key_exists($name, $this->arguments);
    }

    public function getArgument(string $name, mixed $default = null): mixed
    {
        if (\array_key_exists($name, $this->arguments)) {
            return $this->arguments[$name];
        }

        return $default;
    }

    public function getArguments(): iterable
    {
        return $this->arguments;
    }

    public function getPath(): array
    {
        /** @var non-empty-list<non-empty-string> */
        return $this->info->path;
    }

    public function getPathAsString(string $delimiter = self::DEFAULT_PATH_DELIMITER): string
    {
        /** @var non-empty-string */
        return \implode($delimiter, $this->info->path);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getFieldName(): string
    {
        return $this->field->getName();
    }

    public function getFieldAlias(): ?string
    {
        /** @var non-empty-string $alias */
        $alias = \end($this->info->path);

        if ($alias === $this->field->getName()) {
            return null;
        }

        return $alias;
    }

    public function getFieldDefinition(): FieldDefinition
    {
        return $this->field;
    }

    public function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }

    public function getSelectedTypes(): array
    {
        return $this->expectedTypes ??= self::resolvePreferType($this->info);
    }

    public function getSelectedFields(): array
    {
        return \array_keys($this->getSelection());
    }

    public function getSelection(int $depth = 0): array
    {
        return $this->selection[$depth] ??= self::getFieldSelection($this->info, $depth);
    }

    public function isSelected(string $field): bool
    {
        return \in_array($field, $this->getSelectedFields(), true);
    }

    public function isSelectedOneOf(string $field, string ...$fields): bool
    {
        $actual = $this->getSelectedFields();

        foreach ([$field, ...$fields] as $expected) {
            if (\in_array($expected, $actual, true)) {
                return true;
            }
        }

        return false;
    }

    public function isSelectedAllOf(string $field, string ...$fields): bool
    {
        $actual = $this->getSelectedFields();

        foreach ([$field, ...$fields] as $expected) {
            if (!\in_array($expected, $actual, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param int<0, max> $depth
     * @return non-empty-array<non-empty-string, true|non-empty-array>
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private static function getFieldSelection(ResolveInfo $info, int $depth = 0): array
    {
        $fields = [];

        /** @var FieldNode $fieldNode */
        foreach ($info->fieldNodes as $fieldNode) {
            if ($fieldNode->selectionSet) {
                $fold = self::foldSelectionSet($info, $fieldNode->selectionSet, $depth);

                /** @noinspection SlowArrayOperationsInLoopInspection */
                $fields = \array_merge_recursive($fields, $fold);
            }
        }

        return $fields;
    }

    /**
     * @param int<0, max> $descend
     * @return non-empty-array<non-empty-string, true|non-empty-array>
     *
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     */
    private static function foldSelectionSet(ResolveInfo $info, SelectionSetNode $selectionSet, int $descend): array
    {
        $fields = [];

        foreach ($selectionSet->selections as $selectionNode) {
            if ($selectionNode instanceof FieldNode) {
                $fields[$selectionNode->name->value] = $descend > 0 && $selectionNode->selectionSet
                    ? self::foldSelectionSet($info, $selectionNode->selectionSet, $descend - 1)
                    : true;
            } elseif ($selectionNode instanceof FragmentSpreadNode) {
                $spreadName = $selectionNode->name->value;

                if (isset($info->fragments[$spreadName])) {
                    $fragment = $info->fragments[$spreadName];

                    $fold = self::foldSelectionSet($info, $fragment->selectionSet, $descend);

                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $fields = \array_merge_recursive($fold, $fields);
                }
            } elseif ($selectionNode instanceof InlineFragmentNode) {
                $fold = self::foldSelectionSet($info, $selectionNode->selectionSet, $descend);

                /** @noinspection SlowArrayOperationsInLoopInspection */
                $fields = \array_merge_recursive($fold, $fields);
            }
        }

        return $fields;
    }

    /**
     * @return list<non-empty-string>
     */
    private static function resolvePreferType(ResolveInfo $info): array
    {
        $types = [];

        foreach ($info->fieldNodes as $node) {
            if ($node->selectionSet) {
                foreach (self::getSelectionSet($info, $node->selectionSet) as $type) {
                    $types[] = $type;
                }
            }
        }

        return $types;
    }

    /**
     * @return iterable<non-empty-string>
     *
     * @psalm-suppress MoreSpecificReturnType
     */
    private static function getSelectionSet(ResolveInfo $info, SelectionSetNode $set): iterable
    {
        foreach ($set->selections as $selection) {
            if ($selection instanceof InlineFragmentNode) {
                yield $selection->typeCondition->name->value;
            }

            if ($selection instanceof FragmentSpreadNode) {
                $fragment = $info->fragments[$selection->name->value];

                yield $fragment->typeCondition->name->value;
            }
        }
    }

    public function getParentValue(): mixed
    {
        return $this->parent;
    }
}
