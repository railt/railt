# Object

> No description provided yet ='(

```php
use Serafim\Railgun\Types\Schemas\Fields;
use Serafim\Railgun\Types\AbstractObjectType;

class Comment extends AbstractObjectType
{
    // public function getFields(Fields $schema): iterable { ... }
}

class User extends AbstractObjectType
{
    public function getFields(Fields $schema): iterable
    {
        yield 'id'         => $schema->id();
        yield 'created_at' => $schema->string();
        yield 'updated_at' => $schema->string();
        
        // Relation to list of "Comment" type
        yield 'comments'   => $schema->hasMany(Comment::class);
    }
}
```
