# Serialization

## Creation

Just create an example of UserSerializer:

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

## Usage

Serializer contains a little helper methods:

- `collection(Collection $collection): Collection` 
    > No description provided yet ='(

- `items(iterable $items): array`  
    > No description provided yet ='(

- `item($object): array` 
    > No description provided yet ='(
    
- `mapper(): \Closure`  
    > No description provided yet ='(

Example:

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
