<?php
 ini_set("display_errors", 1); 		error_reporting(E_ALL); 
if (isset($_POST["reset-password-submit"])) {
    
    $selector = $_POST["selector"];
    // echo $selector;
    $validator = $_POST["validator"];
    $password = $_POST["pwd"];
    // echo $password;
    $passwordRepeat = $_POST["pwd-repeat"];
    // echo 'him';
    if (empty($password) || empty($passwordRepeat)) {
        header("location: ../create-new-pass.php?newpwd=empty&selector=". $selector . "&validator=" . $validator);
        exit();
    } else if ( $password != $passwordRepeat){
        header("location: ../create-new-pass.php?newpwd=pwdnotsame&selector=". $selector . "&validator=". $validator);
        exit();
    }
    // echo 'him for the 2nd time';
    echo "<br>";
    $currentDate = date("U");
    echo $currentDate;
    echo "<br>";
    @include '../config.php';

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector = ? AND pwdResetExpires >= ? ;";


    $stmt = mysqli_prepare($conn, $sql);
    
    if( false === $stmt){
        echo 'ERROR';
        exit();
    }
    else {
        echo 'goinggg 3rd!';
    }

        mysqli_stmt_bind_param($stmt, "ss", $selector, $currentDate);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        echo "<br>";
        print_r($result);

        if (!$row = mysqli_fetch_assoc($result)){
            echo "<br>";
            echo "Error here.";
            header("location: ../reset_password.php?reset=expired");

            exit();
        } else {
            
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);
            echo 'him going for the 5th';
            if ($tokenCheck === false){
                echo "You need to re-submit your reset request.";
                exit();
            } 
            elseif($tokenCheck === true) {

                $tokenEmail = $row['pwdResetEmail'];

                $sql = "SELECT * FROM users WHERE email = ?;";

                $stmt = mysqli_prepare($conn, $sql);

                if( false === $stmt){
                    echo 'ERROR';
                    exit();
                }
                else {
                    echo 'goinggg at FINAL STAGE!';
                }

                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);

                    echo '1st down';
                    $result = mysqli_stmt_get_result($stmt);
                    if (!$row = mysqli_fetch_assoc($result)){
                        echo "There was an error";
                        exit();
                    } else {

                        $sql = "UPDATE users SET password = ? WHERE email = ?;";

                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "There was an error";
                            exit();
                        }
                        else {
                            echo "2nd down";
                            $newPwdHash = md5($password);
                            mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);

                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail = ?;";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "There was an error";
                                exit();
                            }
                            else {
                                echo '4th down';
                                mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_close($stmt);
                                mysqli_close($conn);
                                echo "punt";
                                header("location:../login_form.php?newpwd=passwordupdated");

                            }
                        }
                        
                            
                    }
                
            }
        }
    

} else {
    header("location:../login_form.php");
}

?>