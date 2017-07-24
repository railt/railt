# Symfony 2.8+ integration

- [symfony/symfony](https://github.com/symfony/symfony)

> No description provided yet ='(

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Http\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MyController
{
    /**
     * @Route("/graphql", methods={"GET", "POST")
     */
    public function someAction(Request $request): JsonResponse
    {
        $endpoint = (new Endpoint('test'))
            ->query('articles', new ArticlesQuery());
            
        $response = $endpoint->request(Request::create($request));
        
        return new JsonResponse($response);
    }
}
```
