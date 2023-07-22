<?php

declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Common\HasDescriptionTrait;

abstract class NamedDefinition extends Definition implements
    NamedDefinitionInterface
{
    use HasDescriptionTrait;

    /**
     * @param non-empty-string $name
     */
    public function __construct(
        protected readonly string $name,
    ) {
        $this->assertValidName($this->name);
    }

    private function assertValidName(string $name): void
    {
        if ($name === '') {
            throw new \InvalidArgumentException('Expected name to be a non-empty string');
        }

        if (!\preg_match('/^[_a-zA-Z][_a-zA-Z0-9]*$/', $name)) {
            $message = 'Names must match /^[_a-zA-Z][_a-zA-Z0-9]*$/, but "%s" does not';
            throw new \InvalidArgumentException(\sprintf($message, $name));
        }
    }

    public function getName(): string
    {
        return $this->name;
    }
}
