<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Linker;

use Phplrt\Source\File;
use Railt\SDL\Ast\Location;
use Railt\SDL\Executor\Context;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;
use Phplrt\Contracts\Parser\Exception\ParserRuntimeExceptionInterface;

/**
 * Class DirectoryLinker
 */
class DirectoryLinker implements LinkerInterface
{
    /**
     * @var string[]
     */
    private const DEFAULT_EXTENSIONS = ['.graphql', '.graphqls'];

    /**
     * @var array|string[]
     */
    private array $extensions;

    /**
     * @var string
     */
    private string $directory;

    /**
     * DirectoryLinker constructor.
     *
     * @param string $directory
     * @param array $extensions
     */
    public function __construct(
        string $directory,
        array $extensions = self::DEFAULT_EXTENSIONS
    ) {
        $this->directory = $directory;
        $this->extensions = $extensions;
    }

    /**
     * @param Context $context
     * @param string|null $name
     * @param int $type
     * @param Location $from
     * @return void
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws ParserRuntimeExceptionInterface
     */
    public function __invoke(Context $context, ?string $name, int $type, Location $from): void
    {
        if ($name === null) {
            return;
        }

        foreach ($this->extensions as $extension) {
            $file = $this->directory . '/' . $name . $extension;

            if (\is_file($file)) {
                $context->compile(File::fromPathname($file));
            }
        }
    }
}
