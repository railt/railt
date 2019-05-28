<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx;

use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\FragmentDefinitionNode;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\InlineFragmentNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Railt\Foundation\Webonyx\Input\PathInfoLoader;
use Railt\Foundation\Webonyx\Input\PreferTypesLoader;
use Railt\Http\Input as BaseInput;
use Railt\Http\RequestInterface;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Class Input
 */
class Input extends BaseInput
{
    use PathInfoLoader;
    use PreferTypesLoader;

    /**
     * @var ResolveInfo
     */
    private $info;

    /**
     * @var FieldDefinition
     */
    private $reflection;

    /**
     * Input constructor.
     *
     * @param RequestInterface $request
     * @param ResolveInfo $info
     * @param FieldDefinition $field
     * @param array $args
     */
    public function __construct(RequestInterface $request, ResolveInfo $info, FieldDefinition $field, array $args = [])
    {
        [$this->info, $this->reflection] = [$info, $field];

        $type = $this->resolveTypeName($field);

        parent::__construct($request, $type, $args);

        $this->withField($this->reflection->getName());
        $this->resolveDefaultArguments($field);
    }

    /**
     * @param int $depth
     * @return array
     */
    public function getRelations(int $depth = 0): array
    {
        return $this->getFieldSelection($depth);
    }

    /**
     * @param int $depth
     * @return array
     */
    private function getFieldSelection(int $depth = 0): array
    {
        $fields = [];

        /** @var FieldNode $fieldNode */
        foreach ($this->info->fieldNodes as $fieldNode) {
            if ($fieldNode->selectionSet) {
                $fold = $this->foldSelectionSet($fieldNode->selectionSet, $depth);

                /** @noinspection SlowArrayOperationsInLoopInspection */
                $fields = \array_merge_recursive($fields, $fold);
            }
        }

        return $fields;
    }

    /**
     * @param SelectionSetNode $selectionSet
     * @param int $descend
     * @return bool[]
     */
    private function foldSelectionSet(SelectionSetNode $selectionSet, int $descend): array
    {
        $fields = [];

        foreach ($selectionSet->selections as $selectionNode) {
            if ($selectionNode instanceof FieldNode) {
                $fields[$selectionNode->name->value] = $descend > 0 && $selectionNode->selectionSet
                    ? $this->foldSelectionSet($selectionNode->selectionSet, $descend - 1)
                    : true;
            } elseif ($selectionNode instanceof FragmentSpreadNode) {
                $spreadName = $selectionNode->name->value;

                if (isset($this->info->fragments[$spreadName])) {
                    /** @var FragmentDefinitionNode $fragment */
                    $fragment = $this->info->fragments[$spreadName];

                    $fold = $this->foldSelectionSet($fragment->selectionSet, $descend);

                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $fields = \array_merge_recursive($fold, $fields);
                }
            } elseif ($selectionNode instanceof InlineFragmentNode) {
                $fold = $this->foldSelectionSet($selectionNode->selectionSet, $descend);

                /** @noinspection SlowArrayOperationsInLoopInspection */
                $fields = \array_merge_recursive($fold, $fields);
            }
        }

        return $fields;
    }

    /**
     * @param string $field
     * @param \Closure|null $then
     * @return bool
     */
    public function wants(string $field, \Closure $then = null): bool
    {
        $depth = \substr_count($field, '.');

        $result = Arr::has($this->getFieldSelection($depth), $field);

        if ($result && $then) {
            $then($this, $field);
        }

        return $result;
    }

    /**
     * @return ResolveInfo
     */
    protected function getResolveInfo(): ResolveInfo
    {
        return $this->info;
    }

    /**
     * @param FieldDefinition $field
     * @return string
     */
    private function resolveTypeName(FieldDefinition $field): string
    {
        return $field->getParent()->getName();
    }

    /**
     * @param FieldDefinition $field
     */
    private function resolveDefaultArguments(FieldDefinition $field): void
    {
        foreach ($field->getArguments() as $argument) {
            if ($argument->hasDefaultValue() && ! $this->has($argument->getName())) {
                $this->withArgument($argument->getName(), $argument->getDefaultValue());
            }
        }
    }
}
