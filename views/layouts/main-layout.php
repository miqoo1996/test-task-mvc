<?php

/**
 * @var $this \App\Core\View\View
 */

?>

<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

        <title>Test Task</title>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12" style="margin-top: 20px;">
                    <?php $this->renderContent() ?>
                </div>
            </div>
        </div>
    </body>
</html>