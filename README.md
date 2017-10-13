# Framework agnostic PHP Pagination component

Light weight, easy drop-in pagination component for PHP applications.

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
    
* `Paginator` - a parent class for any implementations that return the html structure of a pagination. Constructor of `Pagination` class implementation takes 2 arguments:

    * instance of `Pagination`
    * records for a given page as instance of `SSD\Paginator\Collection` or `Illuminate\Support\Collection`
 
Package comes with 2 implementation of `Paginator`:

    * `SelectPaginator`
    * `VueSelectPaginator`
    
The `SelectPaginator` returns the following structure when `render()` method is called on its instance:

```html
<form class="ssd-paginator">
    <a href="http://paginator.app" class="paginator-button">
        <i class="fa fa-angle-left"></i>
    </a>
    <span class="paginator-label">Page</span>
    <select>
        <option value="http://paginator.app">1</option>
        <option value="http://paginator.app/?page=2" selected="selected">2</option>
        <option value="http://paginator.app/?page=3">3</option>
        <option value="http://paginator.app/?page=4">4</option>
        <option value="http://paginator.app/?page=5">5</option>
    </select>
    <span class="paginator-label">of 5</span>
    <a href="http://paginator.app/?page=3" class="paginator-button">
        <i class="fa fa-angle-right"></i>
    </a>
</form>
```

And to make it work, you can support it with a simple `jQuery` such as:

```javascript
$(function() {
    
    $('.ssd-paginator select').on('change', function() {
        window.location.href = $(this).val();
    });
    
});
```

The `VueSelectPaginator` returns the following structure when `render()` method is called on its instance (all entities are decoded for clarity):

```html
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

And to support this implementation, there is a `VueJs` component that ships with this package - you'll find it under `resources/src/js/components/Paginator/Select.vue` - you can attach it to your `Vue` instance as (replacing the path to the component with the correct one for your set up):

```javascript
Vue.component('ssd-paginator', require('./components/Paginator/Select.vue'));
```
To create your own implementations of `Pagination` simply use this class as parent and add required `html()` method.

### Styling

Package comes with a `scss` and compiled `css` stylesheet to support the layout of the `Paginator` implementations that come with a package. Please check `resources/src/scss/app.scss` and `resources/dist/css/app.css`.

### Usage

```php
// import all dependencies

use SSD\Paginator\Request;
use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\SelectPaginator;

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

$paginator = new SelectPaginator($pagination, $chunk);
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

If you don't want to use `Paginator` class implementation, the `Pagination` class has all necessary methods to allow you put together pagination structure directly in your view, for instance to display list of all pages as clickable numbers with current page one , you could so something like this:

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