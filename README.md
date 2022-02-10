# Framework agnostic PHP Pagination component

Light weight, easy drop-in pagination component for PHP 8 applications.

[![Build Status](https://travis-ci.org/sebastiansulinski/php-paginator.svg?branch=master)](https://travis-ci.org/sebastiansulinski/php-paginator)

### Installation

```
composer require sebastiansulinski/php-paginator
```

### Structure

The component consist of 2 main classes:

* `Pagination` - class, to which you pass
    * instance of a `SSD\Paginator\Request` or, if your project already makes use of `\Illuminate\Http\Request`, you can pass instance of it instead.
    * total number of records
    * number of records per page
    * string key representing the query string parameter associated with the current page
    
* `Paginator` - a parent class for any implementations that return the html structure of a pagination. Its constructor takes 2 arguments:

    * instance of `Pagination`
    * records for a given page as instance of `SSD\Paginator\Collection` or `Illuminate\Support\Collection`
 
Package comes with one implementation of `Paginator`:

#### VueSelectPaginator

The `VueSelectPaginator` returns the following structure when `render()` method is called on its instance (all entities are decoded for clarity):

```
<ssd-paginator 
    :options="{
        "1":"http://paginator.app",
        "2":"http://paginator.app/?page=2",
        "3":"http://paginator.app/?page=3",
        "4":"http://paginator.app/?page=4",
        "5":"http://paginator.app/?page=5"
    }" 
    current="http://paginator.app/?page=2" 
    previous="http://paginator.app" 
    next="http://paginator.app/?page=3" 
    first="http://paginator.app" 
    last="http://paginator.app/?page=5" 
    :number-of-pages="5"
></ssd-paginator>
```

And to support this implementation, there is a `VueJs` component that ships with this package - you'll find it under `resources/src/js/components/SsdPaginator`:

```javascript
import { createApp } from 'vue'
import SsdPaginator from './components/SsdPaginator'

createApp({
  components: { SsdPaginator },
}).mount('#app')
```
To create your own implementations of `Paginator` all you have to do is to provide implementation of the `html()` method, which should return the html structure of your pagination layout.

### Styling

SsdPaginator comes pre-formatted using tailwindcss v3, but you can replace its structure using the available slot and apply your own styling as required.

### Usage

```php
// import all dependencies

use SSD\Paginator\Request;
use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\VueSelectPaginator;

// instantiate Pagination class

$pagination = new Pagination(
    Request::capture(),
    160,
    10,
    'page'
);

// get your records as array and pass through to the Collection
// in this example I just use array of numbers and get only a chunk
// of records based on offset and limit, but you'd probably use
// some active model to get only the records you're after

$records = range(1, 160);
$records = new Collection($records);

$chunk = $records->splice(
    $pagination->offset(),
    $pagination->limit()
);

// instantiate SelectPaginator with instance of Pagination and collection of records

$paginator = new VueSelectPaginator($pagination, $chunk);
```

### Displaying records and pagination

```php
// loop through records using Collection::map() and implode() methods

echo $paginator->records()->map(function($record) {
    // ... 
})->implode('');

// or using standard foreach loop

foreach($paginator->records() as $record) {
    // ...
}

// display pagination

echo $paginator->render();
```

### Custom pagination structure

If you don't want to use `Paginator` class implementation, the `Pagination` class has all necessary methods to allow you put together pagination structure directly in your view, for instance to display list of all pages as clickable numbers with current page highlighted using `class="active"`, you could do something like:

```php
$pagination = new Pagination(
    Request::capture(),
    160,
    10,
    'page'
);

echo '<ul>';

echo $pagination->urlList()->map(function(string $url, int $page) use($pagination) {
    $link  = '<li><a href="'.$url.'"';
    $link .= $pagination->current() === $page ? ' class="active"' : null;
    $link .= '>'.$page.'</a></li>';
    return $link;
})->implode('');

echo '</ul>';
```