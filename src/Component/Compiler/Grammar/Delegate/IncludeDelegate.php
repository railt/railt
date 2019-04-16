<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Compiler\Grammar\Delegate;

use Railt\Component\Io\File;
use Railt\Component\Io\Readable;
use Railt\Component\Parser\Ast\Rule;
use Railt\Component\Io\Exception\NotReadableException;
use Railt\Component\Io\Exception\ExternalFileException;
use Railt\Component\Compiler\Exception\IncludeNotFoundException;

/**
 * Class IncludeDelegate
 */
class IncludeDelegate extends Rule
{
    /**
     * @param Readable $from
     * @return Readable
     * @throws NotReadableException
     * @throws ExternalFileException
     */
    public function getPathname(Readable $from): Readable
    {
        $file = $this->getChild(0)->getValue(1);

        foreach (['', '.pp', '.pp2'] as $ext) {
            $path = \dirname($from->getPathname()) . '/' . $file . $ext;

            if (\is_file($path)) {
                return File::fromPathname($path);
            }
        }

        throw (new IncludeNotFoundException(\sprintf('Grammar "%s" not found', $file)))
            ->throwsIn($from, $this->getOffset());
    }
}
