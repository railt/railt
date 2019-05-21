<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Composer;

use Phplrt\Io\File;
use Railt\Json\Exception\JsonException;
use Railt\Json\Validator\Validator;
use Railt\Json\Validator\ValidatorInterface;

/**
 * Class Configuration
 */
class DiscoveryConfiguration
{
    /**
     * @var string
     */
    public const KEY_SCHEMA = 'schema';

    /**
     * @var string
     */
    public const KEY_EXCEPT = 'except';

    /**
     * @var string
     */
    private const EXCEPT_DEPTH_DELIMITER = '.';

    /**
     * @var array
     */
    private $configs;

    /**
     * Configuration constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->configs = $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function filter($data)
    {
        return $this->filterExcepted($data, $this->configs[self::KEY_EXCEPT] ?? []);
    }

    /**
     * @param mixed $data
     * @param array|string[] $haystack
     * @return array|bool|float|int|string|null
     */
    private function filterExcepted($data, array $haystack)
    {
        switch (true) {
            case \is_scalar($data):
                return \in_array((string)$data, $haystack, true) ? null : $data;

            case \is_array($data):
                $walker = new ArrayWalker(static function (array $current) use ($haystack) {
                    return ! \in_array(\implode(self::EXCEPT_DEPTH_DELIMITER, $current), $haystack, true);
                });

                return $walker->filter($data);

            default:
                return $data;
        }
    }

    /**
     * @param Section $section
     * @throws \JsonException
     * @throws JsonException
     */
    public function validate(Section $section): void
    {
        if ($validator = $this->getValidator()) {
            $section->validate($validator)->throwOnError();
        }
    }

    /**
     * @return ValidatorInterface|null
     * @throws JsonException
     */
    public function getValidator(): ?ValidatorInterface
    {
        if (isset($this->configs[self::KEY_SCHEMA])) {
            $file = File::fromPathname($this->configs[self::KEY_SCHEMA]);

            return Validator::fromFile($file);
        }

        return null;
    }
}
