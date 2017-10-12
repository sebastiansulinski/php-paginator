# Framework agnostic PHP Pagination component

## (Project under development!!!)

Light weight, easy drop-in pagination component for PHP applications.

### Installation

```
composer require sebastiansulinski/php-paginator
```

### Usage

The component consist of 2 main classes:

* `Pagination` - class, to which you pass
    * instance of a `SSD\Paginator\Request` or, if your project already makes use of `\Illuminate\Http\Request`, you can pass instance of it instead.
    * total number of records
    * number of records per page
    * string key representing the query string parameter associated with the current page
    
* `Paginator` - any class extending it - component comes with implementation of `SelectPaginator`, constructor of which takes:
    * instance of `Pagination`
    * records for a given page
    
To create your own implementations of `Pagination` simply use this class as parent and add required `html()` method.

```php
use SSD\Paginator\Request;
use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\SelectPaginator;

$pagination = new Pagination(
    Request::capture(),
    160,
    10,
    'page'
);

$records = new Collection(range(1, 160));

$chunk = $records->splice(
    $pagination->offset(),
    $pagination->limit()
);

$paginator = new SelectPaginator($pagination, $chunk);
```

### Displaying records and pagination

```php
// loop through records
foreach($paginator->records() as $record) {
    // ...
}

// display pagination
echo $paginator->render();
```

If you don't want to use `Paginator` class implementation, the `Pagination` class has all necessary methods to allow you put together pagination structure directly in your view.