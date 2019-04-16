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
use Railt\Component\Exception\ExternalException;
use Railt\Component\Io\Exception\NotReadableException;
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
     * @throws ExternalException
     */
    public function getPathname(Readable $from): Readable
    {
        $name = $this->getChild(0)->getValue(1);
        $dir = \dirname($from->getPathname());

        foreach (['', '.pp', '.pp2'] as $ext) {
            $path = $dir . '/' . $name . $ext;

            if (\is_file($path)) {
                return File::fromPathname($path);
            }
        }

        $error = \sprintf('Can not find the grammar file "%s" in "%s"', $name, $dir);

        $exception = new IncludeNotFoundException($error);
        $exception->throwsIn($from, $this->getOffset());

        throw $exception;
    }
}
