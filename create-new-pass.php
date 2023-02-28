<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create new password</title>
    <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
  <main>
    <div class="form-container">

    <?php
        $selector = $_GET["selector"];
        $validator = $_GET["validator"];

        if (empty($selector) || empty($validator)) {
            echo "Could not validate your request";
        }
        else {
            if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
                ?>

                <form action="includes/reset-password-form.php" method="post">
                    <input type="hidden" name="selector" value="<?php echo $selector ?>" >
                    <input type="hidden" name="validator" value="<?php echo $validator ?>" >
                    <input type="password" name="pwd" required placeholder="enter new password"... >
                    <input type="password" name="pwd-repeat" required placeholder="repeat new password"... >
                    <input type="submit" name="reset-password-submit" value="Reset Password" class="form-btn">
                    <?php
                        if (isset($_GET["newpwd"])){
                            if($_GET["newpwd"] == 'empty'){
                                echo '<span style="color:red;">Please input password into both fields</span>';
                            }
                            elseif($_GET["newpwd"] == 'pwdnotsame'){
                                echo '<span style="color:red;">Password is not the same</span>';
                            }
                        };
                    ?>
                </form>
                    <?php
                    
                    
                    
            }
        };
    ?>

    </div>
  </main>
</body>
</html>