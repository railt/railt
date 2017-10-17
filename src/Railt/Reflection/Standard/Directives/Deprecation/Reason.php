<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Directives\Deprecation;

use Railt\Reflection\Base\BaseArgument;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Contracts\Types\NamedTypeDefinition;
use Railt\Reflection\Contracts\Types\TypeDefinition;
use Railt\Reflection\Standard\Directives\Deprecation;

/**
 * Class Reason
 */
class Reason extends BaseArgument
{
    private const ARGUMENT_TYPE = 'String';

    private const ARGUMENT_DEFAULT_VALUE = 'No longer supported';

    private const ARGUMENT_DESCRIPTION = 'You can either supply a reason argument ' .
        'with a string value or not supply one and receive a "No longer supported" ' .
        'message when introspected';

    /**
     * Reason constructor.
     * @param Document $document
     * @param DirectiveType $type
     */
    public function __construct(Document $document, DirectiveType $type)
    {
        $this->parent       = $this;
        $this->document     = $document;
        $this->name         = Deprecation::REASON_ARGUMENT;
        $this->description  = self::ARGUMENT_DESCRIPTION;
        $this->defaultValue = self::ARGUMENT_DEFAULT_VALUE;
    }

    /**
     * @return NamedTypeDefinition|TypeDefinition
     */
    public function getType(): NamedTypeDefinition
    {
        return $this->document->getType(self::ARGUMENT_TYPE);
    }
}
