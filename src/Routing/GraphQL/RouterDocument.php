<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\GraphQL;

use Railt\Io\File;
use Railt\Reflection\Base\Document;
use Railt\SDL\Reflection\Dictionary;

/**
 * Class RouterDocument
 */
class RouterDocument extends Document
{
    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * RouterDocument constructor.
     * @param Dictionary $dictionary
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->name       = 'Router additional directives';
        $this->file       = File::fromSources('# Generated');
        $this->types      = $this->createTypes();
        $this->dictionary = $dictionary;
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }

    /**
     * @return array
     */
    private function createTypes(): array
    {
        return [
            RouteDirective::DIRECTIVE_NAME => new RouteDirective($this),
        ];
    }
}
