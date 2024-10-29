<?php

    require_once './config.php';

    $p = santize_array($_POST);
    $error_msg = "";

    if (isset( $_SESSION["username"] )) {

        header("location: ./index.php");
        exit;
    }
      
    
    if ( values_in_array( $p, "username", "password", "password-confirm" ) ) {

        if ( strlen($p["password"]) >= 8 ) {
            
            
            if ( $p["password"] == $p["password-confirm"] ) {
                
                $qr = $db->query("SELECT * FROM user WHERE username='$p[username]'");
        
                if ($qr->rowCount() == 0 ) {
        
                    $ps = password_hash($p["password"], PASSWORD_DEFAULT);
                    insert_row( 'user', 'username', $p['username'], 'password', $ps );
                    $_SESSION["username"] = $p["username"];
        
                    header("location: ./index.php");
                    
                } else {
        
                    $error_msg = "username already taken";
                    
                }
                
            }else{
                
                $error_msg = "passwords don't match";
                
            }
        }else{

            $error_msg = "password must have at least 8 characters";

        }
    
    }

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>Signin Template · Bootstrap v5.0</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">



    <!-- Bootstrap core CSS -->
    <link href="./src/css/bootstrap.min.css" rel="stylesheet">

    <link href="./src/css/style.css" rel="stylesheet">
</head>

<body class="vh-100 ">
<?php require_once './nav.php' ?>

    <div class="container text-center mx-auto my-5 border shadow w-75">
        <main class="form-signin p-5">
        <?php if ($error_msg != "") {?>
        <div class="alert alert-dismissible alert-warning">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h4 class="alert-heading">Warning!</h4>
            <p class="mb-0"><?=$error_msg?></p>
        </div>
    <?php } ?>
            <form class="" method="POST" >
                <h1 class="h3 mb-3 fw-normal">Sign up</h1>
                <hr>

                <div class="form-floating mb-3">
                    <input name="username" type="text" class="form-control" id="floatingInput" placeholder="Username">
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="password-confirm" type="password" class="form-control" id="RefloatingPassword" placeholder="Retype Password">
                    <label for="floatingPassword">Retype Password</label>
                </div>

                <div class="mb-3">
                    <p>Already have an account ?
                        <a href="./login.php">Login</a>
                    </p>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
                <p class="mt-5 mb-3 text-muted">&copy; 2023</p>
            </form>
        </main>
    </div>


    <script src="./src/js/main.js"></script>
</body>

</html>