<?php

    require_once './config.php';

    $p = santize_array($_POST);
    $error_msg = "";
    $can_answer = isset($_SESSION["username"]);
    $user_id = 0;
    $my_rating = null;

    if ( empty( $_GET["id"] ) || is_numeric($_GET["id"]) == false || $db->query("SELECT * from question WHERE id='$_GET[id]'")->rowCount() == 0  ) {

        header("location: ./index.php");
        exit;

    }

        
   
    if ($can_answer) {

        $user_id = $db->query("SELECT id from user WHERE username='$_SESSION[username]'")->fetchAll()[0][0];

        if ( values_in_array( $p, "answer" )  ) {

            
    
            insert_row( 'answer', 'answer', $p['answer'], "answer_to", $_GET["id"], "answered_by", $user_id );
            
    
        }
        
        if ( values_in_array( $p, "reply" )  ) {
    
                
        
            insert_row( 'reply', 'reply', $p['reply'], "answer", $p['answer_id'], 'reply_by', $user_id );
            
    
        }
        
        if ( values_in_array( $p, "rating" )  ) {
    
            
            if ( $db->query("SELECT * FROM rating WHERE rated_by=$user_id AND rated_answer=$p[answer_id]")->rowCount() > 0 ) {

                $db->query("UPDATE rating SET rating=$p[rating] WHERE rated_by=$user_id AND rated_answer=$p[answer_id]");
    
            }else{
    
                insert_row( 'rating', 'rating', $p['rating'], "rated_answer", $p['answer_id'], 'rated_by', $user_id );
            
            }
            
    
        }



        $temp = $db->query("SELECT * from rating WHERE rated_by=$user_id");
        $my_ratings = $temp->rowCount() > 0  ? $temp->fetchAll() : [];
    }
    

    


    $question = $db->query("SELECT user.username, question.* FROM question INNER JOIN user ON user.id = question.asked_by WHERE question.id='$_GET[id]'")->fetchAll()[0];
    
    $answers = $db->query("SELECT answer.*, user.username, ( SELECT AVG( rating.rating ) FROM rating WHERE rating.rated_answer = answer.id ) AS rating FROM answer INNER JOIN user ON user.id = answer.answered_by WHERE answer_to = $_GET[id]")->fetchAll();
    $temp = $db->query("SELECT reply.*, user.username from reply INNER JOIN user ON reply.reply_by = user.id")->fetchAll();
    $replies = [];
    foreach ($temp as $reply) {
        if (isset( $replies[$reply["answer"]] )) {
            
            $replies[$reply["answer"]][] = $reply;
            
        }else{
            
            $replies[$reply["answer"]] = [$reply];
        }
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
    <title>QUESTION</title>
</head>

<body class="bg-light">
    <!-- NAV -->
    <?php require_once './nav.php' ?>

    <div class="container mt-5  ">
        <div class="card rounded shadow">
            <!-- QUESTION -->
            <div class="card-header p-3 d-flex align-items-center justify-content-between text-white bg-primary">
            <small class="fw-bold"><?= $question["title"] ?></small>
                
                <h6>question by : <?= $question["username"] ?></h6>
            </div>
            <div class="card-body">
                <pre><?= $question["question"] ?>
                </pre>
            </div>
                <?php  if ($can_answer ) { ?>
                <div class="card-footer">
                <!-- RATE QUESTION -->
                <!-- TODO: -->
                  
                    </div>
             <?php } ?>

            <!-- DIVIDER -->
            <hr>

            <!-- ANSWERS -->
            <?php foreach ( $answers as $answer ) { ?>
                <div class="ms-4 my-4 border-start">

                    <!-- answer 1 -->
                    <div class="answer">
                        <div class="card-header d-flex align-items-center justify-content-between text-white bg-info">
                            <small class="fw-bold"></small>
                            <h6> answer by :  <?= $answer["username"] ?></h6>
                        </div>
                        <div class="card-body">
                            <pre><?= $answer["answer"] ?>
                            </pre>
                        </div>
                        <div class="card-footer">

                            <!-- Comment Toggler -->
                            <h5>Rating: <?= floatval( $answer["rating"]) ?></h5>

                            <?php if ($can_answer) {
                                $my_rating = [];
                                foreach ($my_ratings as $rating) {
                                    if (  $rating["rated_answer"] == $answer["id"]) {
                                        $my_rating = $rating["rating"];
                                    }
                                }
                                ?>

                                <div class="d-flex">

                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdropLive<?= $answer["id"] ?>" style="white-space: nowrap;">Add Comment</button>
                                    <div class="container">
                            <form action="" method="post">
                            <div class="rating d-flex align-items-center ">
                                <fieldset class="p-2" >

                                    <input type="hidden" name="answer_id" value="<?= $answer["id"] ?>">
                                    <input <?= $my_rating == 1 ? "checked" : "" ?> type="radio" name="rating" class="form-check-input ms-2" value="1" id="1">
                                    <label class="form-check-label" for="1">1</label>
                                    <input <?= $my_rating == 2 ? "checked" : "" ?> type="radio" name="rating" class="form-check-input ms-2" value="2" id="2">
                                    <label class="form-check-label" for="2">2</label>
                                    <input <?= $my_rating == 3 ? "checked" : "" ?> type="radio" name="rating" class="form-check-input ms-2" value="3" id="3">
                                    <label class="form-check-label" for="3">3</label>
                                    <input <?= $my_rating == 4 ? "checked" : "" ?> type="radio" name="rating" class="form-check-input ms-2" value="4" id="4">
                                    <label class="form-check-label" for="4">4</label>
                                    <input <?= $my_rating == 5 ? "checked" : "" ?> type="radio" name="rating" class="form-check-input ms-2" value="5" id="5">
                                    <label class="form-check-label" for="5">5</label>
                                </fieldset>
                                <button type="submit" class="btn btn-info" > rate </button>
                                
                            </div>
                        </form>
                        </div>
                  
                                </div>
                            <?php } ?>

                            <!-- Modal body -->
                            <div class="modal fade" id="staticBackdropLive<?= $answer["id"] ?>" data-bs-backdrop="static"
                                data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel"
                                aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title " id="staticBackdropLiveLabel">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body bg-light ">
                                            <div class="m-4 p-3 border rounded shadow ">
                                                <input type="hidden" name="answer_id" value="<?= $answer["id"] ?>">
                                                <label for="ans" class="form-label mb-2">Write a comment</label>
                                                <textarea name="reply" id="ans" rows="10" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <hr>
                    <!-- Comments -->
                    <div class="ms-4 my-4 border-start accordion-item">

                        <div class="accordion-header text-center p-2" id="headingOne">
                            <button class="btn btn-outline-success text-center  w-50" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne<?=$answer["id"]?>" aria-expanded="true"
                                    aria-controls="collapseOne">
                                Show Comments
                            </button>
                        </div>
                        <!-- all -->
                        <div id="collapseOne<?=$answer["id"]?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <!-- comment 1 -->
                            <?php if ( isset( $replies[$answer["id"]] ) ){
                            
                            
                            
                            foreach ($replies[$answer["id"]] as $reply ) { ?>
                                
                            <div class="comment">

                                <div class="card-header d-flex align-items-center justify-content-between text-white bg-success">
                                    <small class="fw-bold"></small>
                                    <h6>comment by : <?= $reply["username"] ?></h6>
                                </div>
                                <div class="card-body">
                                    <pre><?= $reply["reply"] ?>
                                    </pre>
                                </div>
                            </div>
                          <?php }} ?>
                        </div>
                    </div>

                </div>
            <?php } ?>
            <!-- DIVIDER -->
            <hr>

            <!-- ADD ANSWER -->
        <?php if ($can_answer) {?>

            <div class="m-4 p-3 border rounded bg-info text-white">
                <form action="" method="post">

                    <label for="ans" class="form-label mb-2">Write an answer</label>
                    <textarea name="answer" id="ans" rows="10" class="form-control"></textarea>
                    <button class="btn btn-outline-light w-100 mt-3 " type="submit">Submit form</button>
                </form>
            </div>
        <?php } ?>




        </div>

    </div>
    
    <script src="./src/js/main.js"></script>
    <script src="./src/js/bootstrap.bundle.min.js"></script>
    <script src="./src/js/index.js"></script>

</body>

</html>