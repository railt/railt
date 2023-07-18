<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Bridge\Webonyx;

use Railt\TypeSystem\InputFieldDefinition;

/**
 * @template-extends Builder<InputFieldDefinition, array>
 */
final class InputObjectFieldBuilder extends Builder
{
    public function build(object $input): array
    {
        assert($input instanceof InputFieldDefinition, self::typeError(
            InputFieldDefinition::class,
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
