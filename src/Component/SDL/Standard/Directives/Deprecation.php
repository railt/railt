<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Standard\Directives;

use Railt\Component\SDL\Base\Definitions\BaseDirective;
use Railt\Component\SDL\Contracts\Definitions\Directive\Location;
use Railt\Component\SDL\Contracts\Dependent\ArgumentDefinition;
use Railt\Component\SDL\Contracts\Document;
use Railt\Component\SDL\Standard\Directives\Deprecation\Reason;
use Railt\Component\SDL\Standard\StandardType;

/**
 * Class Deprecation
 *
 * @see https://github.com/graphql/graphql-js/pull/384
 */
final class Deprecation extends BaseDirective implements StandardType
{
    /**
     * Deprecation directive name
     */
    public const DIRECTIVE_TYPE_NAME = 'deprecated';

    /**
     * Deprecation reason argument
     */
    public const REASON_ARGUMENT = 'reason';

    /**
     * Deprecation constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->name = self::DIRECTIVE_TYPE_NAME;
        $this->deprecationReason = self::RFC_IMPL_DESCRIPTION;
        $this->locations = Location::TARGET_GRAPHQL_SDL;

        $argument = $this->createReasonArgument();
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @return ArgumentDefinition
     */
    private function createReasonArgument(): ArgumentDefinition
    {
        return new Reason($this->getDocument(), $this);
    }
}
