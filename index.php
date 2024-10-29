
<?php



    require_once './config.php';

    $p = santize_array($_POST);
    $error_msg = "";
    $can_answer = isset($_SESSION["username"]);

    $latest_questions = $db->query("SELECT question.*, user.username from question INNER JOIN user on user.id=question.asked_by ORDER BY id DESC LIMIT 10")->fetchAll();
    // $top_questions = $db->query("SELECT question.*, user.username from question INNER JOIN user ON user.id=question.asked_by ORDER BY ( SELECT AVG( rating.rating ) FROM rating WHERE rating.rated_answer = question.id ) DESC LIMIT 10")->fetchAll();
    $top_answers = $db->query("SELECT answer.*, user.username, ( SELECT AVG( rating.rating ) FROM rating WHERE rating.rated_answer = answer.id ) AS ratingg from answer INNER JOIN user ON user.id=answer.answered_by ORDER BY ratingg DESC LIMIT 10")->fetchAll();

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


    if ( values_in_array( $p, "question", "title" ) ) {

            
    
        insert_row( 'question', 'question', $p['question'], "title", $p["title"], "asked_by", $db->query("SELECT id from user WHERE username='$_SESSION[username]'")->fetchAll()[0][0] );
        
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
   <?php require_once './nav.php' ?>
    <div class="container">
        <!-- HEADER -->
        <!-- TODO:  toggle hidden -->
        <div class="row vh-100 align-items-center">
            <div class="col-md-6 col-12 text-md-start text-center">
                <h1 class="display-3 mb-3">Login to view all question</h1>
                <?php if( $can_answer == false ) { ?>
                    <a class="btn btn-lg btn-outline-info w-50" href="./login.php">Login</a>
                <?php } ?>
            </div>
            <div class="col-md-6 col-12">
                <img src="./src/img.svg" alt="">
            </div>
        </div>

        <?php if ( $can_answer ) {?>
        <!-- Post Question -->
        <div class="row  mb-5">
            <div class=" my-5 p-5 border border-primary rounded  text-center shadow">
            <form action="" method="post">
            <label for="quest" class="form-label mb-2 bg-primary border p-3 text-white">Write a question</label>
                            <input type="text" name="title" class="form-control mb-2" placeholder="title">
            <textarea name="question" id="quest" rows="10" class="form-control" placeholder="question"></textarea>
                <button class="btn btn-primary w-75 mt-4 " type="submit">Submit form</button>
                </form>
            </div>
        </div>
        <?php } ?>

        <!-- Top & last questions -->
        <div class="row">

            <!-- Top Questions -->
            <div class="col-md-6 col-12 p-2 mb-5  shadow rounded">
                <h2 class="text-center">Top 10 Answers</h2>
                <div class="container">

                    <!-- Search -->
                    <form class="d-flex w-100  mx-auto border shadow mb-3 p-3" action="./search.php">
                    <input name="q" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-dark" type="submit">Search</button>
                </form>

                <?php foreach ( $top_answers as $answer ) {?>
                        <div class="card rounded shadow">
                            <div
                                class="card-header d-flex align-items-center justify-content-between text-white bg-info">
                                <small class="fw-bold"></small>
                                <h6>answer by : <?= $answer["username"] ?></h6>
                            </div>
                            <div class="card-body">
                                <pre><?= $answer["answer"] ?>
                                </pre>
                                <a href="./question.php?id=<?= $answer["answer_to"] ?>">Read more</a>

                            </div>

                            <!-- TODO: -->
                            <div class="card-footer text-white bg-dark">
                                Rating <?= $answer["ratingg"] ? floatval($answer["ratingg"]) : 0 ?>/5
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <hr>
            </div>

            <!-- Latest Questions -->
            <div class="col-md-6 col-12 p-2 mb-5  shadow rounded">
                <h2 class="text-center">Latest Questions</h2>
                <div class="container">

                    <!-- Search -->
                    <form class="d-flex w-100  mx-auto border shadow mb-3 p-3" action="./search.php">
                    <input name="q" class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-dark" type="submit">Search</button>
                </form>

                    <?php foreach ( $latest_questions as $question ) {?>
                        <div class="card rounded shadow">
                            <div
                                class="card-header d-flex align-items-center justify-content-between text-white bg-primary">
                                <small class="fw-bold"><?= $question["title"] ?></small>
                                <h6>question by : <?= $question["username"] ?></h6>
                            </div>
                            <div class="card-body">
                            <pre><?=  substr($question["question"], 0, 10)  ?>...</pre>
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