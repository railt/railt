## Сериализация

### Создание серализатора

В качестве примера создадим сериализатор пользователя:

```php
use Serafim\Railgun\Serializers\AbstractSerializer;

class UserSerializer extends AbstractSerializer
{
    /**
     * @param MyUserObject $user
     */
    public function toArray($user): array
    {
        return [
            'id'         => $user->getId(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt(),
        ];
    }
}
```

### Использование

Для того, чтобы использовать сериализатор можно воспользоваться его статическими методами:

- `collection(Collection $collection): Collection` 
    > Принимает коллекцию в качестве 
аргумента и применяет функцию сераилизации к каждому аргументу, возвращая новую коллекцию.

- `items(iterable $items): array`  
    > Принимает любой итератор в качестве 
аргумента, применяет функцию сериализации к каждому элементу и возвращает новый массив.

- `item($object): array` 
    > Применяет указанный метод сераилизации к одному элементу.
    
- `mapper(): \Closure`  
    > Возвращает ссылку на метод сериализации, например для передачи 
куда-либо в качестве аргумента

Пример:

```php
class UserQuery extends AbstractQuery
{
    // ...
    public function resolve($value, array $arguments = [])
    {
        // $repo = UsersRespoitory::class
    
        return UserSerializer::items($repo->findAll());
    }
}
``` 
