<?php

require '../vendor/autoload.php';

use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\Request;
use SSD\Paginator\VueSelectPaginator;

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

$vueSelectPaginator = new VueSelectPaginator($pagination, $chunk);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Paginator</title>
    <link href="./dist/css/app.css" rel="stylesheet">
    <script src="./dist/js/app.js" defer></script>
</head>
<body class="bg-gray-100">

    <div id="app" class="py-16">

        <div class="container mx-auto">

            <nav class="border border-gray-300 rounded overflow-hidden mb-4">
                <p class="p-4 bg-gray-800 text-white">
                    Records
                </p>
                <div>
                    <?php foreach ($vueSelectPaginator->records() as $record) { ?>
                        <a class="p-4 flex items-center odd:bg-white">
                            <div class="shrink mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <div class="grow">
                                Record number <?php echo $record; ?>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </nav>

            <div class="has-text-centered">
                <?php echo $vueSelectPaginator->render(); ?>
            </div>

        </div>

    </div>

</body>
</html>