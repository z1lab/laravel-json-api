## Installation

```
composer require z1lab/laravel-json-api
```

Define .env keys:

`CACHE_LIFETIME` for set the default cache lifetime.

`PAGINATION_SIZE` for define the page size of collection results (Ex: GET index method).

## Introduction

Laravel Json Api is a minimal library for normalize Laravel Apps to return Json Api results.

Before start take a look in the [Json API Spec](https://jsonapi.org/).

This package takes advantage of [Laravel Eloquent Api Resources](https://laravel.com/docs/5.7/eloquent-resources) and some simple dependencies for improve the Json API pattern:

[API Version Control](https://github.com/juampi92/api-resources)

[API Exception Handler](https://github.com/esbenp/heimdal)

[CORS Middleware](https://github.com/barryvdh/laravel-cors)

[HTTP Cache](https://github.com/barryvdh/laravel-httpcache)

After starts, check the packages official docs for the default configuration of each one.

## Usage

Just extends the required classes and apply the error handling.

### Error handling

Change the render() method in the App\Exceptions\Handler:

``` php
use Z1lab\JsonApi\Exceptions\ApiHandler;

public function render($request, Exception $exception)
    {
        if($request->wantsJson()) {
            $handler = new ApiHandler($this->container);

            return $handler->render($request, $exception);
        }

        return parent::render($request, $exception);
    }
```

### API Controllers

In your controller class just extends the ApiController and set the constructor according the following example:

``` php
use Z1lab\JsonApi\Http\Controllers\ApiController;

class AwesomeController extends ApiController
{
    public function __construct(AwesomeRepository $repository)
    {
        parent::__construct($repository, 'Awesome');
    }
}
```

The fist parameter sets the repository for access the datasets; The second parameter defines the namespace to JsonResource class.

The ApiController extends all base methods from Laravel BaseController and delivery the initial methods for your ApiResourceController:

`public function index() {}`

`public function show(string $id) {}`

`public function destroy(string $id) {}`

`public function makeResource($obj) {}`

`public function collectResource($collection) {}`

You only have to implement the `store()` and `update()` methods with your FormRequest rules.

### Api FormRequests

For optimal handle errors in yours FormRequests Just extends the ApiFormRequest class:

``` php
use Z1lab\JsonApi\Http\Requests\ApiFormRequest;

class AwesomeFormRequest extends ApiFormRequest {}
```

### Api Repositories

In your repository class just extends the ApiRepository and set the constructor according the following example:

``` php
use Z1lab\JsonApi\Repositories\ApiRepository;

class AwesomeRepository extends ApiRepository
{
    public function __construct(Awesome $model)
    {
        parent::__construct($model, 'awesome');
    }
}
```

The fist parameter sets the model for access the data; The second parameter defines the cache prefix name.

The ApiRepository delivery some common methods for you handle your data accross the app.

`public function create(array $data) {}`

`public function update(array $data, string $id) {}`

`public function destroy(string $id) {}`

`public function find(string $id, array $with = []) {}`

`public function list(int $items = 0) {}`

`public function findWhere(string $column, $value, array $with = []) {}`

## License

Laravel Json Api is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).