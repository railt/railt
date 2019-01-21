<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\SDL\Language;

use Railt\Io\File;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Schema\CompilerInterface;
use Railt\Tests\SDL\Helpers\CompilerStubs;
use Railt\Tests\SDL\TestCase;

/**
 * Class AbstractReflectionTestCase
 */
abstract class AbstractLanguageTestCase extends TestCase
{
    use CompilerStubs;

    /**
     * @param string $body
     * @return array
     * @throws \Throwable
     */
    public function dataProviderDocuments(string $body): array
    {
        try {
            $result = [];

            foreach ($this->getDocuments($body) as $document) {
                $result[] = [$document];
            }

            return $result;
        } catch (\Throwable $e) {
            echo $e;
            throw $e;
        }
    }

    /**
     * @param string $body
     * @return iterable|Document[]
     * @throws \Exception
     */
    protected function getDocuments(string $body): iterable
    {
        $readable = File::fromSources($body);

        foreach ($this->getCompilers() as $compiler) {
            yield $compiler->compile($readable);
        }
    }

    /**
     * @return array|CompilerInterface[]
     * @throws \LogicException
     * @throws \Exception
     */
    public function dateCompilersProvider(): array
    {
        $result = [];

        foreach ($this->getCompilers() as $compiler) {
            $result[] = [$compiler];
        }

        return $result;
    }
}
