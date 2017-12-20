<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\GraphQL;

use Railt\Reflection\Base\Definitions\BaseDirective;
use Railt\Reflection\Contracts\Definitions\Directive\Location;
use Railt\Reflection\Contracts\Document;
use Railt\Routing\GraphQL\RouteDirective\ActionArgument;
use Railt\Routing\GraphQL\RouteDirective\OperationArgument;

/**
 * Class RouteDirective
 */
class RouteDirective extends BaseDirective
{
    /**
     * Route directive name
     */
    public const DIRECTIVE_NAME = 'route';

    /**
     * Route directive description
     */
    public const DIRECTIVE_DESCRIPTION = '';

    /**
     * RouteDirective constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;

        $this->name        = static::DIRECTIVE_NAME;
        $this->description = static::DIRECTIVE_DESCRIPTION;

        $this->locations = $this->createLocations();
        $this->arguments = $this->createArguments($document);
    }

    /**
     * @return array
     */
    private function createLocations(): array
    {
        return [
            Location::TARGET_OBJECT,
            Location::TARGET_FIELD_DEFINITION,
        ];
    }

    /**
     * @param Document $document
     * @return array
     */
    private function createArguments(Document $document): array
    {
        return [
            ActionArgument::ARGUMENT_NAME    => new ActionArgument($document, $this),
            OperationArgument::ARGUMENT_NAME => new OperationArgument($document, $this),
        ];
    }
}
