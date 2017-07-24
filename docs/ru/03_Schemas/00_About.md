# Схемы

Все доступные классы схем (`Serafim\Railgun\Types\Schemas\***`) предоставляют почти общий 
интерфейс, который содержит список доступных типов и возможность их расширения.

## Скалярные типы

Подробный список всех скалярных типов [находится где-то тут](http://graphql.org/learn/schema/#scalar-types).

- `$schema->id()`
    > Скалярный тип ID. Тип ID представляет собой уникальный идентификатор, 
    часто используемый для повторной загрузки объекта по его идентификатору
    или в качестве идентификатора для кеша. Тип ID сериализуется так же, 
    как String; Однако определение его как ID 
    означает, что он не предназначен для чтения человеком.
        
- `$schema->ids()`
    > Список из ID.
    
- `$schema->integer()`
    > Скалярный тип Int. 32-битный числовой тип, содержащий знак.
    
- `$schema->integers()`
    > Список из Int.
    
- `$schema->string()`
    > Скалярный тип String. UTF-8 последовательность символов.
    
- `$schema->strings()`
    > Список из String.
    
- `$schema->boolean()`
    > Скалярный тип Boolean. `Rebel` или `Empire`. Шутка. Просто `true` или `false`.
    
- `$schema->booleans()`
    > Список из Boolean.
    
- `$schema->float()`
    > Скалярный тип Float. Число с плавающей точкой, со знаком и с двойной точностью.
    
- `$schema->floats()`
    > Список из Float.

## Псевдонимы

Каждая схема, содержит метод `extend(string $name, \Closure $callback)`, 
предназначенный для раширения типов своими "псевдонимами" (т.е. не на уровне языка, 
а на уровне внутренней логики приложения). 

Пример типичных (упрощённых) структур данных:

```php
class User extends AbstractObjectType
{
    public function getFields(Fields $schema): iterable
    {
        yield 'login' => $schema->string();
        yield 'email' => $schema->string();
        yield 'created_at' =>  $schema->string();
        yield 'updated_at' =>  $schema->string();
    }
}

class Article extends AbstractObjectType
{
  public function getFields(Fields $schema): iterable
  {
      yield 'title' => $schema->string();
      yield 'body' => $schema->string();
      yield 'created' =>  $schema->string();
      yield 'updated' =>  $schema->string();
  }
}
```

Мы можем его упросить:

```php
use Serafim\Railgun\Types\Schemas\Fields;

// Получаем доступ к схеме Fields
$fields = $endpoint->getRegistry()->schema(Fields::class);

// Расширяем её и добавляем тип `timestamps`, который добавляет даты.
$fields->extend('timestamps', function (strgin $c = 'created_at', string $u = 'updated_at') use ($fields) {
    yield $c => $fields->string();
    yield $u => $fields->string();
});
```

А теперь попробуем написать тоже самое, используя "псевдонимы":

```php
class User extends AbstractObjectType
{
    public function getFields(Fields $schema): iterable
    {
        yield 'login' => $schema->string();
        yield 'email' => $schema->string();
  
        yield from $schema->timestamps();
    }
}

class Article extends AbstractObjectType
{
  public function getFields(Fields $schema): iterable
  {
      yield 'title' => $schema->string();
      yield 'body' => $schema->string();
      
      yield from $schema->timestamps('created', 'updated');
  }
}
```
