This chapter shows you how to add a library support without using any frameworks.

# Native php integration

> No description provided yet, only code. GG WP EZ

```php
use Serafim\Railgun\Endpoint;
use Serafim\Railgun\Requests\Factory;

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| So let us turn on the lights! For a start, we need to create our point of
| interaction (aka Endpoint). Let's create it and add there references to
| requests, mutations and other "magic" things.
|
*/

$endpoint = new Endpoint('test');

$endpoint->query('example', new ExampleQuery());

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$response = $endpoint->request(Factory::create());

/*
|--------------------------------------------------------------------------
| Send The Response
|--------------------------------------------------------------------------
|
| Finally send our very important answer in json format and
| close the connection.
|
*/

header('Content-Type: application/json');

echo json_encode($response);
```
