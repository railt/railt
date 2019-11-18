<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery;

use Composer\Composer;
use Composer\Script\Event;
use Railt\Discovery\Repository\ReaderInterface;
use Railt\Discovery\Repository\Composer\Reader;
use Railt\Discovery\Repository\PackageInterface;
use Railt\Discovery\Exception\ValidationException;

/**
 * Class Generator
 */
class Generator
{
    /**
     * @var string
     */
    private const MSG_EXPORT = '  Loading <comment>%s</comment> configuration from <info>%s</info>';

    /**
     * @var string
     */
    private const MSG_ERROR = '  <error> × %s </error>';

    /**
     * @var ReaderInterface
     */
    private ReaderInterface $reader;

    /**
     * @var string
     */
    private string $root;

    /**
     * Manifest constructor.
     *
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->reader = new Reader($composer);
        $this->root = $composer->getConfig()
            ->getConfigSource()
            ->getName();
    }

    /**
     * @param Event $event
     * @throws ValidationException
     * @throws \Throwable
     */
    public static function generate(Event $event): void
    {
        [$result, $io] = [[], $event->getIO()];

        $manifest = new static($event->getComposer());

        foreach ($manifest->read() as $section => $package) {
            $io->write(\sprintf(self::MSG_EXPORT, $section, $package->getName()));

            // Validation
            $errors = $manifest->validate($section, $package);
            foreach ($errors as $error) {
                $io->writeError(\sprintf(self::MSG_ERROR, $error->getMessage()));
            }

            // Merge
            if (\count($errors) === 0) {
                $result[$section] = $result[$section] ?? [];
                $result[$section] = \array_merge_recursive($result[$section], $package->getExtra($section));
            }
        }

        $class = $manifest->save($result);

        $io->write('<comment>' . $class->getName() . '</comment> was generated successfully');
    }

    /**
     * @return \Traversable|PackageInterface[]
     */
    public function read(): \Traversable
    {
        foreach ($this->reader->getPackages() as $package) {
            foreach ($this->reader->getExportedSections() as $section) {
                if ($extra = $package->getExtra($section)) {
                    yield $section => $package;
                }
            }
        }
    }

    /**
     * @param string $section
     * @param PackageInterface $package
     * @return array|ValidationException[]
     */
    public function validate(string $section, PackageInterface $package): array
    {
        return $this->reader->validate($section, $this->toJsonObject($package->getExtra($section)));
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function toJsonObject(array $data)
    {
        $json = \json_encode($data, \JSON_THROW_ON_ERROR);

        return \json_decode($json, false, 512, \JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $data
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    public function save(array $data): \ReflectionClass
    {
        $reflection = new \ReflectionClass(Manifest::class);

        $result = $this->modify(\dirname($reflection->getFileName()), \var_export($data, true));
        $result = $this->renderTemplate($result);

        \file_put_contents($reflection->getFileName(), $result);

        return $reflection;
    }

    /**
     * @param string $body
     * @return string
     */
    private function renderTemplate(string $body): string
    {
        return \str_replace('${configuration}', $body, $this->getTemplate());
    }

    /**
     * @return string
     */
    private function getTemplate(): string
    {
        return \trim(\file_get_contents(__DIR__ . '/../resources/manifest.generated.txt'));
    }

    /**
     * @param string $directory
     * @param string $data
     * @return string
     */
    private function modify(string $directory, string $data): string
    {
        $root = \dirname($this->root);

        $fn = function (string $lexeme, int $token) use ($root, $directory): string {
            if ($token !== \T_CONSTANT_ENCAPSED_STRING) {
                return $lexeme;
            }

            $lexeme = $this->stringToFqnClass($lexeme);
            $lexeme = $this->pathnameToConstant($root, $directory, $lexeme);

            return $lexeme;
        };

        return $this->replaceArrayTokens($data, $fn);
    }

    /**
     * @param string $lexeme
     * @return string
     */
    private function stringToFqnClass(string $lexeme): string
    {
        if (\strlen($lexeme) < 2) {
            return $lexeme;
        }

        $fqn = \str_replace('\\\\', '\\', \substr($lexeme, 1, -1));

        if (\class_exists($fqn) || \interface_exists($fqn)) {
            return '\\' . \trim($fqn, '\\') . '::class';
        }

        return $lexeme;
    }

    /**
     * @param string $root
     * @param string $directory
     * @param string $lexeme
     * @return string
     */
    private function pathnameToConstant(string $root, string $directory, string $lexeme): string
    {
        for ($i = 0; $i < 6; ++$i) {
            $replacements = ["'$directory", '__DIR__ . \'' . \str_repeat('/..', $i)];

            $lexeme = \str_replace($replacements[0], $replacements[1], $lexeme);

            if ($directory === $root) {
                return $lexeme;
            }

            $directory = \dirname($directory);
        }
    }

    /**
     * @param string $php
     * @param \Closure $each
     * @return string
     */
    private function replaceArrayTokens(string $php, \Closure $each): string
    {
        $result = [];

        foreach (\token_get_all('<?php ' . $php) as $i => $token) {
            if ($i === 0) {
                continue;
            }

            $result[] = \is_string($token) ? $token : $each($token[1], $token[0]) ?? $token[1];
        }

        return \implode('', $result);
    }
}
