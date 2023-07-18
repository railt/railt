<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use Railt\TypeSystem\ArgumentDefinition;

/**
 * @template-extends Builder<ArgumentDefinition, array>
 */
final class FieldArgumentBuilder extends Builder
{
    public function build(object $input): array
    {
        assert($input instanceof ArgumentDefinition, self::typeError(
            ArgumentDefinition::class,
            $input,
        ));

        $result = [
            'name' => $input->getName(),
            'description' => $input->getDescription(),
            'type' => $this->type($input->getType()),
        ];

        if ($input->hasDefaultValue()) {
            $result['defaultValue'] = $this->value($input->getDefaultValue());
        }

        return $result;
    }
}
