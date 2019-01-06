<?php

require "vendor/autoload.php";

use SSD\Paginator\Request;
use SSD\Paginator\Collection;
use SSD\Paginator\Pagination;
use SSD\Paginator\SelectPaginator;
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

$selectPaginator = new SelectPaginator($pagination, $chunk);

$vueSelectPaginator = new VueSelectPaginator($pagination, $chunk);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paginator</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css" rel="stylesheet">
    <link href="./resources/dist/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
    <script src="./resources/dist/js/app.js" defer></script>
</head>
<body>

<div id="app">

    <section class="hero">

        <div class="hero-body">

            <div class="container">

                <h1 class="title">Using SelectPaginator with jQuery</h1>

                <nav class="panel">
                    <p class="panel-heading">
                        repositories
                    </p>
                    <?php foreach($selectPaginator->records() as $record) { ?>
                    <a class="panel-block">
                        <span class="panel-icon">
                            <i class="fa fa-book"></i>
                        </span>
                        Record number <?php echo $record; ?>
                    </a>
                    <?php } ?>
                </nav>

                <div class="has-text-centered">
                    <?php echo $selectPaginator->render(); ?>
                </div>

            </div>

        </div>

    </section>

    <section class="hero">

        <div class="hero-body">

            <div class="container">

                <h1 class="title">Using VueSelectPaginator with VueJs component</h1>

                <nav class="panel">
                    <p class="panel-heading">
                        repositories
                    </p>
                    <?php foreach($vueSelectPaginator->records() as $record) { ?>
                    <a class="panel-block">
                        <span class="panel-icon">
                            <i class="fa fa-book"></i>
                        </span>
                        Record number <?php echo $record; ?>
                    </a>
                    <?php } ?>
                </nav>

                <div class="has-text-centered">
                    <?php echo $vueSelectPaginator->render(); ?>
                </div>

            </div>

        </div>

    </section>

</div>

</body>
</html>