<?php

    require_once './config.php';

    $p = santize_array($_POST);
    $error_msg = "";
    $user_id = 0;

    

        
   
    if ( isset($_SESSION["username"]) ) {

        $user_id = $db->query("SELECT id from user WHERE username='$_SESSION[username]'")->fetchAll()[0][0];
       
        if ( empty( $_GET["id"] ) || is_numeric($_GET["id"]) == false || $db->query("SELECT * from answer WHERE id='$_GET[id]' AND answered_by=$user_id")->rowCount() == 0  ) {

            header("location: ./index.php");
            exit;
    
        }
    
    }else{

        header("location: ./index.php");
        exit;

    }
    

    if ( values_in_array( $p, "action" )  ) {


        if ($p["action"] == "edit") {

            $db->query("UPDATE answer SET answer='$p[question]' WHERE id= $p[answer_id]");
            
            
        }else{
            
            $db->query("DELETE FROM answer WHERE id= $p[answer_id]");

            header("location: ./user.php");


        }
            
    
        

    }
    
   


    $answer = $db->query("SELECT user.username, answer.* FROM answer INNER JOIN user ON user.id = answer.answered_by WHERE answer.id='$_GET[id]'")->fetchAll()[0];
  
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./src/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./src/css/style.css" />
    <title>StackOverFlow</title>
</head>

<body class="bg-light">
<?php require_once './nav.php' ?>

    <div class="container mt-5  ">
        <div class="card rounded shadow">
            <!-- QUESTION -->
            <!-- TODO: -->
            <!-- edit, Delete -->
            <div class="card-header p-3 d-flex align-items-center justify-content-between text-white bg-info">
                <small class="fw-bold"></small>
                <h6><?= $answer["username"] ?></h6>
            </div>
            <div class="card-body">
                <form class="" method="post">
                    <input type="hidden" name="answer_id" value="<?= $answer["id"] ?>" >
                    <textarea name="answer" rows="10" class="form-input w-100" ><?= $answer["answer"] ?>
                    </textarea>
                    <div class="mt-3 ">

                        <button class="btn me-3 px-4 btn-warning " name="action" value="edit" >Edit</button>
                        <button class="btn btn-danger px-4 " name="action" value="delete" >Delete</button>
                    </div>
                </form>
            </div>

            <!-- DIVIDER -->
            <hr>



        </div>

    </div>

    <script src="./src/js/bootstrap.bundle.min.js"></script>
    <script src="./src/js/index.js"></script>
</body>

</html>