<?php
require_once '../inc/controller.php';
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Rated</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<style>
    .desc {
        position: relative;
        display: inline-block;
    }

    .desc .desctext {
        visibility: hidden;
        width: 620px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -60px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .desc .desctext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .desc:hover .desctext {
        visibility: visible;
        opacity: 1;
    }

    img {
        width: 70px;
        height: 70px;
    }

    a {
        text-decoration: none;
    }

    a:hover {
        background-color: wheat;
        text-decoration: none;
        color: black;
    }

    .container-fluid {
        flex-direction: column;
    }

    .text-muted {
        margin-bottom: 5%;
    }
</style>

<body>
    <div class="container-fluid col-6 d-flex justify-content-center">

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/imdb.php">Scrape IMDb</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pornhub.php">Scrape Pornhub</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/toprated.php">Scrape TopRated on IMDb</a>
                    </li>
                </ul>
            </div>
        </nav>
        <h1 class="text-muted">Top Rated Movies on IMDB BELLOW 8.5 GRADE</h1>
        <br>
        <table id="dtable" class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">ID</th>
                    <th scope="col">Title & Watch Trailer</th>
                    <th scope="col">IMDb Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $top->getAll();
                while ($row = $result->fetch()) {
                    echo "<tr>
                        <td>
                            <img src='{$row['thumbnail']}'>
                        </td>
                        <td> 
                            {$row['id']}. 
                        </td>
                        <td class='desc'>
                            <a href='{$row['trailer']}' target='_blank'>
                                 {$row['title']}
                            </a>
                            <span class='desctext'>{$row['description']}</span>
                        </td>
                        <td>
                            {$row['grade']}
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
    </div>

    </table>
</body>
<footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $().ready(() => {
            $('#dtable').dataTable();
        });
    </script>
</footer>

</html>