<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Standard\Directives\Deprecation;

use Railt\Component\SDL\Base\Dependent\BaseArgument;
use Railt\Component\SDL\Contracts\Definitions\DirectiveDefinition;
use Railt\Component\SDL\Contracts\Definitions\TypeDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Component\SDL\Standard\Directives\Deprecation;
use Railt\Component\SDL\Standard\GraphQLDocument;

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
     *
     * @param Document|GraphQLDocument $document
     * @param DirectiveDefinition $type
     */
    public function __construct(Document $document, DirectiveDefinition $type)
    {
        $this->parent = $type;
        $this->document = $document;
        $this->name = Deprecation::REASON_ARGUMENT;
        $this->description = self::ARGUMENT_DESCRIPTION;

        $this->defaultValue = self::ARGUMENT_DEFAULT_VALUE;
        $this->hasDefaultValue = true;
    }

    /**
     * @return TypeDefinition
     */
    public function getTypeDefinition(): TypeDefinition
    {
        return $this->document->getDictionary()->get(self::ARGUMENT_TYPE, $this);
    }
}
