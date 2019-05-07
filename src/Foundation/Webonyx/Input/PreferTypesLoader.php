<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Input;

use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\InlineFragmentNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Type\Definition\ResolveInfo;
use Railt\Component\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Trait PreferTypes
 */
trait PreferTypesLoader
{
    /**
     * @var bool
     */
    private $initializedPreferTypes = false;

    /**
     * @return ResolveInfo
     */
    abstract protected function getResolveInfo(): ResolveInfo;

    /**
     * @return void
     */
    private function bootPreferTypesLoader(): void
    {
        if (! $this->initializedPreferTypes) {
            $this->initializedPreferTypes = true;

            $types = $this->resolvePreferType($this->reflection, $this->getResolveInfo());

            $this->setPreferType(...$types);
        }
    }

    /**
     * @return iterable
     */
    public function getPreferTypes(): iterable
    {
        $this->bootPreferTypesLoader();

        return $this->preferTypes;
    }

    /**
     * @param FieldDefinition $field
     * @param ResolveInfo $info
     * @return array|string[]
     */
    private function resolvePreferType(FieldDefinition $field, ResolveInfo $info): array
    {
        $types = [];

        foreach ($info->fieldNodes as $node) {
            if ($node->selectionSet) {
                foreach ($this->getSelectionSet($info, $node->selectionSet) as $type) {
                    $types[] = $type;
                }
            }
        }

        if (! \count($types)) {
            $types[] = $field->getTypeDefinition()->getName();
        }

        return $types;
    }

    /**
     * @param ResolveInfo $info
     * @param SelectionSetNode $set
     * @return iterable|string[]
     */
    private function getSelectionSet(ResolveInfo $info, SelectionSetNode $set): iterable
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
}
