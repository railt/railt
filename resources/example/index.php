<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\Request;
use Serafim\Railgun\Routing\Router;
use Serafim\Railgun\Runtime\RequestInterface;

require __DIR__ . '/../../vendor/autoload.php';

$request = Request::create();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $router = new class extends Router {
        /**
         * @param RequestInterface $request
         * @return iterable
         */
        public function resolve(RequestInterface $request): iterable
        {
            yield 'user' => $this->belongsTo(UserQuery::class);

            yield $this->route('{any}/id')->where('any', '.*?') => $this->id();
        }

        /**
         * @return Closure
         */
        private function id(): \Closure
        {
            return function(RequestInterface $request) {
                $result = 'Id of user ' . $request->getPath();

                return $request->has('upper')
                    ? mb_strtoupper($result) . ' + ' . $request->get('upper')
                    : $result;
            };
        }
    };

    $endpoint = $request->has('schema')
        ? Endpoint::sources($request->get('schema'), $router)
        : Endpoint::filePath(__DIR__ . '/gql/schema.graphqls', $router);

    // Request
    $response = $endpoint->request($request);

    // Response
    header('Content-Type: application/json');
    echo json_encode($response);
    die;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/graphiql/0.10.2/graphiql.min.css" />
    <link rel="stylesheet" href="./out/railgun.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/15.5.4/react-dom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/graphiql/0.10.2/graphiql.min.js"></script>
</head>
<body>
    <div style="display: none" id="schemaValue"><?php
        if ($request->has('schema')) {
            echo htmlspecialchars($request->get('schema'));
        } else {
            echo htmlspecialchars(file_get_contents(__DIR__ . '/gql/schema.graphqls'));
        }
    ?></div>
    <div id="graphiql">Loading...</div>
    <div id="schema" style="line-height: 20px;font-size: 14px;"></div>
    <script src="./out/railgun.js"></script>
</body>
</html>

