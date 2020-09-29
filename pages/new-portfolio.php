<?php
require "../config/configuration.php"; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>New Portfolio</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->

</head>

<body class="text-center">
    <header>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4 class="text-white">About</h4>
                        <p class="text-muted">This app has been created for educational purposes to test the cloud computing skills wih OpenStack.</p>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4 class="text-white">Contact</h4>
                        <ul class="list-unstyled">
                            <li><a href="../actions/action-logout.php" class="text-white">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container d-flex justify-content-between">
                <a href="./storage.php" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="mr-2" viewBox="0 0 24 24" focusable="false">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                        <circle cx="12" cy="13" r="4" /></svg>
                    <strong>Cloud Storage</strong>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>
    <div class="row text-center mt-5">
        <div class="col-md-5"></div>
        <div class="col-md-3">
            <form class="form-signin" action="../actions/action-portfolio.php" method="POST" enctype="multipart/form-data">
                <h1 class="h2">New Portfolio</h1>
                <h1 class="h3 mb-3 font-weight-normal">Upload new Portfolio</h1>
                <label for="inputName" class="sr-only">Title</label>
                <input type="text" name="title" id="inputName" class="form-control" placeholder="Title" required autofocus>

                <label for="inputDesc" class="sr-only">Description</label>
                <input type="text" name="description" id="inputEmail" class="form-control" placeholder="Description" required autofocus>

                <label for="inputPassword" class="sr-only"></label>
                <input type="file" name="picture" class="form-control" placeholder="Picture" required autofocus>

                <div class="checkbox mb-3">
                    <label>
                        <a href="./storage.php"> Show all Portfolios </a>
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Save Portfolio Now</button>
                <p class="mt-5 mb-3 text-muted">Created by Cloud Computing Experts Group 5</p>
            </form>
        </div>
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script>
    window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')
</script>
<script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

</html>