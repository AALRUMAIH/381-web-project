
<?php



require_once './config.php';

$p = santize_array($_POST);
$g = santize_array($_GET);
$error_msg = "";
$can_answer = isset($_SESSION["username"]);

if( isset($g["q"])  == false ){
    
    header("location: ./index.php");

}

$query = trim($g["q"]);
$questions = $db->query("SELECT question.*, user.username from question INNER JOIN user on user.id=question.asked_by WHERE question.question LIKE '%$query%' OR  question.title LIKE '%$query%'  ORDER BY id DESC")->fetchAll();

$temp = fetch_table("rating");
$ratings = [];

foreach ($temp as $rate ) {

    
    
    // the rating array contains both the sum of ratings and the number of times rated 
    if (isset( $ratings[$rate["rated_answer"]] )) {

        $ratings[$rate["rated_answer"]]["amount"] += $rate["rating"];
        $ratings[$rate["rated_answer"]]["counts"] ++;
        
    }else{

        $ratings[$rate["rated_answer"]]["amount"] = $rate["rating"];
        $ratings[$rate["rated_answer"]]["counts"] = 1;

    }

}


if ( values_in_array( $p, "question" ) ) {

        

    insert_row( 'question', 'question', $p['question'], "asked_by", $db->query("SELECT id from user WHERE username='$_SESSION[username]'")->fetchAll()[0][0] );
    
    header("location: ./question.php?id=".$db->lastInsertId());


}


?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="./src/css/bootstrap.min.css" />
<link rel="stylesheet" href="./src/css/style.css" />
<title>MAIN</title>
</head>

<body class="bg-light">
<!-- NAV -->
<?php require_once './nav.php' ?>

<div class="container">
   

    <!-- Top & last questions -->
    <div class="row">


        <!-- Latest Questions -->
        <div class=" col-12 p-2 mb-5  shadow rounded">
            <h2 class="text-center">Search results</h2>
            <div class="container">

                <!-- Search -->
                <form class="d-flex w-100  mx-auto border shadow mb-3 p-3" action="./search.php">
                    <input name="q" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-dark" type="submit">Search</button>
                </form>

                <?php foreach ( $questions as $question ) {?>
                    <div class="card rounded shadow">
                        <div
                            class="card-header d-flex align-items-center justify-content-between text-white bg-primary">
                            <small class="fw-bold"><?= $question["title"] ?></small>

                            <h6>question by : <?= $question["username"] ?></h6>
                        </div>
                        <div class="card-body">
                        <pre><?=  substr($question["question"], 0, 10)  ?>...
                            </pre>
                            <a href="./question.php?id=<?= $question["id"] ?>">Read more</a>

                        </div>

                        <!-- TODO: -->
                        <div class="card-footer text-white bg-dark">
                        </div>
                    </div>
                <?php } ?>
            
            </div>
            <hr>

        </div>
    </div>
</div>

<script src="./src/js/bootstrap.bundle.min.js"></script>
<script src="./src/js/index.js"></script>
</body>

</html>