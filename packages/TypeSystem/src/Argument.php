<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\TypeSystem;

use Railt\TypeSystem\Type\InputTypeInterface;

/**
 * Class Argument
 */
class Argument extends Definition
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var string|null
     */
    public ?string $description = null;

    /**
     * @var InputTypeInterface
     */
    public InputTypeInterface $type;

    /**
     * @var Value|null
     */
    public ?Value $defaultValue = null;

    /**
     * @param string $name
     * @param InputTypeInterface $type
     * @return static
     */
    public static function new(string $name, InputTypeInterface $type): self
    {
        return new static(\compact('name', 'type'));
    }
}
