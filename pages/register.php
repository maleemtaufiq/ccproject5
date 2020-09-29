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
    <title>Register</title>

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
    <link href="../assets/css/signin.css" rel="stylesheet">
</head>

<body class="text-center">


    <form class="form-signin" action="../actions/action-register.php" method="POST">
        <h1 class="h2">Cloud Group 5</h1>
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputName" class="sr-only">Name</label>
        <input type="text" name="name" id="inputName" class="form-control" placeholder="Name" required autofocus>

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required autofocus>

        <label for="inputPassword" class="sr-only">City</label>
        <input type="text" name="city" id="inputPassword" class="form-control" placeholder="City" required autofocus>

        <label for="inputPassword" class="sr-only">Phone</label>
        <input type="text" name="phone" id="inputPassword" class="form-control" placeholder="Phone" required autofocus>

        <div class="checkbox mb-3">
            <label>
                <a href="../index.php"> Already have an acocunt? Login Now </a>
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register Now</button>
        <p class="mt-5 mb-3 text-muted">Created by Cloud Computing Experts Group 5</p>
    </form>


</body>

</html>