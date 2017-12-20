<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Routing\GraphQL;

use Railt\Reflection\Base\BaseDocument;
use Railt\Reflection\Filesystem\File;

/**
 * Class RouterDocument
 */
class RouterDocument extends BaseDocument
{
    /**
     * RouterDocument constructor.
     */
    public function __construct()
    {
        $this->file  = File::fromSources('# Generated');
        $this->types = $this->createTypes();
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
