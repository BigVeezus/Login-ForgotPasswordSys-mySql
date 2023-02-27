<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset password</title>
    <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <div class="form-container">
        <form action="includes/reset-request.php" method="post">
            <p>An e-mail will be sent to you with instructions to reset your password</p>
            <input type="text" name="email" placeholder="enter email address">
            <input type="submit" name="reset-request-submit" value="Send reset link" class="form-btn">
            <p><a href="login_form.php">Go back to log in</a></p>
        </form>
        <?php
            if(isset($_GET["reset"])){
                if ($_GET["reset"] == "success"){
                    echo '<span class="signupsuccess">Check your e-mail.</span>';
                }
            }
        
        ?>
    
    </div>
</body>
</html>