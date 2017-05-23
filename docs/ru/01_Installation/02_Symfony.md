В этой главе рассказывается, как добавить поддержку библиотеки в Symfony Framework.

## Установка

- `composer require serafim/railgun`

## Интеграция с Symfony 2.8+

- [symfony/symfony](https://github.com/symfony/symfony)

>  Пока нет описания, только код.

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\Factory;
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
            
        $response = $endpoint->request(Factory::create($request));
        
        return new JsonResponse($response);
    }
}
```
