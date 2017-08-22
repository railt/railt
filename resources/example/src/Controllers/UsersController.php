<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Example;

use Railgun\Adapters\RequestInterface;

/**
 * Class UsersController
 * @package Example
 */
class UsersController
{
    /**
     * @var int
     */
    private static $lastUserFakeId = 1;

    /**
     * Support function
     *
     * @return array
     */
    private function createFakeUser(): array
    {
        return [
            'id'        => ++self::$lastUserFakeId,
            'login'     => 'Fake User #' . self::$lastUserFakeId,
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
        ];
    }

    /**
     * Support function
     *
     * @param RequestInterface $request
     * @return int
     */
    private function count(RequestInterface $request): int
    {
        $count = $request->get('count', 10);
        return max(1, min($count, 100));
    }

    /**
     * @param RequestInterface $request
     * @return iterable
     */
    public function indexAction(RequestInterface $request): iterable
    {
        for ($i = 1; $i <= $this->count($request); ++$i) {
            yield $this->createFakeUser();
        }
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function showAction(RequestInterface $request): array
    {
        return $this->createFakeUser();
    }
}
