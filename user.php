<?php



    require_once './config.php';

    $p = santize_array($_POST);
    
    $error_msg = "";

    if ( isset(  $_SESSION["username"] ) == false) {

        header("location: ./sign-in.php");
        exit;
    }
    

    if ( values_in_array( $p, "question", "title" ) ) {

            
    
        insert_row( 'question', 'question', $p['question'], "title", $p["title"], "asked_by", $db->query("SELECT id from user WHERE username='$_SESSION[username]'")->fetchAll()[0][0] );
        
        header("location: ./question.php?id=".$db->lastInsertId());


    }

    $my_questions = $db->query("SELECT question.*, user.username from question INNER JOIN user on user.username='$_SESSION[username]' WHERE asked_by = user.id")->fetchAll();
    $my_answers = $db->query("SELECT answer.*, ( SELECT AVG( rating.rating ) FROM rating WHERE rating.rated_answer = answer.id ) AS ratingg, question.id AS question_id, question.question from answer INNER JOIN question on answer.answer_to = question.id INNER JOIN user on user.username='$_SESSION[username]' WHERE answered_by = user.id")->fetchAll();
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

    <div class="container">
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
        <div class="row mt-5">
            <div class="col-md-6 col-12 p-2 mb-5  border rounded">
                <h2 class="text-center">Questions Posted</h2>
                <div class="container">
                    <form class="d-flex w-100  mx-auto border shadow mb-3 p-3" action="./search.php">
                        <input class="form-control me-2" type="search" placeholder="Search" name="q" aria-label="Search">
                        <button class="btn btn-outline-dark" type="submit">Search</button>
                    </form>

                    <?php foreach ($my_questions as $question ) {?>
        

                        <div class="card rounded shadow">
                            <div
                                    class="card-header d-flex align-items-center justify-content-between text-white bg-primary">
                                    <small class="fw-bold"><?= $question["title"] ?></small>

                                <h6><?= $question["username"] ?></h6>
                            </div>
                            <div class="card-body">
                            <pre><?=  substr($question["question"], 0, 10)  ?>...
                                </pre>
                                <a href="./question.php?id=<?= $question["id"] ?>">Read more</a>
                            </div>

                            <div class="card-footer text-white bg-dark d-flex justify-content-between">
                                <div>


                                </div>
                                <div>
                                    <a class="btn btn-warning" href="./myQuestion.php?id=<?= $question["id"] ?>">edit</a>

                                </div>
                            </div>
                       
                        </div>
                    <?php } ?>
                    <hr>
    
              
                </div>
                <hr>
            </div>
            <div class="col-md-6 col-12 p-2 mb-5  border rounded">
                <h2 class="text-center">Answers Posted</h2>
                <div class="container">
                <form class="d-flex w-100  mx-auto border shadow mb-3 p-3" action="./search.php">
                        <input class="form-control me-2" type="search" placeholder="Search" name="q" aria-label="Search">
                        <button class="btn btn-outline-dark" type="submit">Search</button>
                    </form>

                    <?php foreach ($my_answers as $answer ) {?>
       
                        <div class="card rounded shadow">
                            <div
                                class="card-header d-flex align-items-center justify-content-between text-white bg-primary">
                                <small class="fw-bold"></small>
                                <a href="./question.php?id=<?= $answer["question_id"] ?>"> <h6> answer to : <?= substr( $answer["question"], 0, 10 )."..." ?></h6></a>
                            </div>
                            <div class="card-body">
                                <pre><?= $answer["answer"] ?>
                                </pre>
                                <a href="./question.php?id=<?= $answer["question_id"] ?>">Go to question</a>
                            </div>

                            <div class="card-footer text-white bg-dark d-flex justify-content-between">
                                <div>

                                    Rating: <?= $answer["ratingg"] ? floatval($answer["ratingg"]) : 0 ?> / 5

                                </div>
                                <div>
                                    <a class="btn btn-warning" href="./myAnswer.php?id=<?= $answer["id"] ?>">edit</a>

                                </div>
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