<?php require "config/configuration.php"; ?>
<?php
if(isLogin()) {
    redirect(ADMIN_PAGES . 'dashboard/');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>BA Portal</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Brand Ambassador Portal" name="description" />
        <meta content="Easer Technologies https://easertechnologies.com" name="author" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/pages/css/login.min.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    
    <body class=" login">
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <div class="text-center">
            <a href="index">
                <img style="width: 250px;margin-top: 12px;" src="assets/pages/img/logo-big.png" alt="" /> </a>
        </div>
            
          <?php if(isset($_SESSION['response']) && $_SESSION['response'] == 'invalid') { ?>
                    <br>
                    <div class="alert alert-danger text-center  fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                    <h3>Incorrect Credentials</h3>
                    Invalid Email or password.</div>

         <?php  unset($_SESSION['response']);  } ?>
            <form class="login-form"  method="post" role="form" id="form_login" action="actions/action-login.php">
                <h3 class="form-title font-green">Sign In</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Enter your email and password. </span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Email</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" required="true" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password"  required="true" /> 
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn green uppercase">Login</button>                    
                    <!--<label class="rememberme check mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1" />Remember
                        <span></span>
                    </label>-->
                </div>
            </form>
        </div>

        <div class="copyright"> Powered By <a href="https://www.easertechnologies.com" target="_blank" style="color: #ffffff;">Easer Technologies</a></div>
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/login.min.js" type="text/javascript"></script>
    </body>
</html>