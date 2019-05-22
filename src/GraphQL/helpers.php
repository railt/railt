<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Phplrt\Ast\RuleInterface;
use Phplrt\Io\File;
use Phplrt\Io\Readable;
use Railt\GraphQL\Parser;

if (! \function_exists('\\graphql')) {
    /**
     * @param string|Readable|\SplFileInfo $sources
     * @return RuleInterface
     */
    function graphql($sources): RuleInterface
    {
        if (\is_string($sources)) {
            $sources = \is_file($sources)
                ? File::fromPathname($sources)
                : File::fromSources($sources);
        }

        if ($sources instanceof \SplFileInfo) {
            $sources = File::fromPathname($sources->getPathname());
        }

        return (new Parser())->parse($sources);
    }
}
