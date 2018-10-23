<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Provider;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SymfonyProvider
 */
class SymfonyProvider extends Provider
{
    /**
     * @var Request
     */
    private $request;

    /**
     * SymfonyProvider constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    protected function isJson(): bool
    {
        $server = $this->request->headers->get(self::CONTENT_TYPE_KEY, self::CONTENT_TYPE_DEFAULT);

        return $this->request->getContentType() === 'json' || $this->matchJson($server);
    }

    /**
     * @return array
     */
    protected function getJson(): array
    {
        try {
            return $this->parseJson($this->request->getContent(false));
        } catch (\LogicException $e) {
            return [];
        }
    }

    /**
     * @return iterable
     */
    protected function getRequestArguments(): iterable
    {
        return \array_merge(
            $this->request->query->all(),
            $this->request->request->all()
        );
    }
}
