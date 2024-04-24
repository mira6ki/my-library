<?php require_once 'autoload.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My library</title>
        <link rel="stylesheet" href="public/css/styles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-..." crossorigin="anonymous">
    </head>
    <body>
    <div class="container">
        <h1>My library</h1>
        <div class="controls row">
            <div class="col-md-4">
                <input type="text" id="searchInput" placeholder="Search by author or book">
            </div>
            <div class="col-md-4">
                <input type="file" id="xmlFileInput">

            </div>
            <div class="col-md-4">
                <button type="button" id="loadFromDirectory">Load from directory</button>
            </div>
        </div>
        <div id="bookTableContainer"></div>
    </div>
    <script src="public/js/books.js"></script>
    </body>
</html>