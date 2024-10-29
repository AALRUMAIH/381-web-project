 <!-- NAV -->
 <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow" aria-label="Twelfth navbar example">
        <div class="container-fluid">
            <?= isset($_SESSION["username"]) ? $_SESSION["username"] : "" ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10"
                    aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
            <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="./index.php">Q&A</a>
                    </li>
                    <?php if ( isset($_SESSION["username"]) ) { ?> 
                        <li class="nav-item">
                            <a class="nav-link" href="./user.php">Profile</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="./logout.php">Logout</a>
                        </li>

                        <?php }else{?>
                            <li class="nav-item">
                                <a class="nav-link" href="./login.php">Login</a>
                            </li>
                    <?php }?>
                </ul>
            </div>
        </div>
    </nav>

    