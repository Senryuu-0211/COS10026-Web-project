
<?php
session_start();
if (isset($_SESSION["uid"])) {
    header("Location: manage.php");
}
?>
<?php include_once 'header.inc'; ?>
<body>
<?php include_once 'menu.inc'; ?>
    <div class="form_login">
        
        <?php
        $email_error = null;
        $password_error = null;
        if (isset($_POST["login"])) {

            require_once "settings.php";
                $email = mysqli_real_escape_string($connect, $_POST["email"]);
                $pass = mysqli_real_escape_string($connect, $_POST["password"]);

                $query = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($connect, $query);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($user) {
                if ($pass == $user["password"]) {  
                    session_start();
                    session_regenerate_id();
                    $user_session_id = session_id();

                    $update = "
                    UPDATE users
                    SET user_session_id = '".$user_session_id."' 
                    WHERE id = '".$user["id"]."'
                    ";
                    $connect->query($update);
                    $_SESSION['id'] = $user['id'];
                    $_SESSION["uid"] = $user_session_id;
                    header("Location: manage.php");
                    
                }else{
                    $password_error = "Password does not match!";
                }
            }else{
                $email_error = "Incorrect email or password!";
            }
        }
        ?>

        <section class="login-section">
        <form action="login.php" method="post">
            <h1 id="login-title">Login</h1>
            <div class="inputbox">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control" required>
                <label for="email">Email</label>
                <p class="alert-danger">
                    <?php echo $email_error ?>
                </p>
            </div>
            <div class="inputbox">
                <input type="password" name="password" class="form-control" required><i class="fas fa-lock"></i>
                <label for="password">Password</label>
                <p class="alert-danger">
                    <?php echo $password_error ?>
                </p>
            </div>
            <div class="submit-button">
                <input class="login-submit" type="submit" value="Login now" name="login">
            </div>
            <div class="back-to-home"><p>Not a manager?</p><a href="index.php">Back to home page</a></div>
        </form>
        </section>
    </div>
</body>
</html>
