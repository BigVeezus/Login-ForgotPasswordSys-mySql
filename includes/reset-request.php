<?php

    ini_set("display_errors", 1); 		error_reporting(E_ALL); 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    

    require '../phpmailer/src/Exception.php';
    require '../phpmailer/src/PHPMailer.php';
    require '../phpmailer/src/SMTP.php';
    require '../vendor/autoload.php';


    $envpath = '../';
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable("../");
    $dotenv->load();


    @include 'config.php';

if (isset($_POST["reset-request-submit"])) {
    
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "http://localhost/loginFormPHP/loginphp/create-new-pass.php?selector=" . $selector . "&validator=" . bin2hex($token);

    $expires = date("U") + 1800;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = mysqli_connect('localhost','root','','Practise Database');

    $userEmail = $_POST["email"];

    $sql = "SELECT * FROM users WHERE email = ?;";

    $stmt = mysqli_prepare($conn, $sql);
    if( false === $stmt){
        echo 'mysqli prepare ERROR';
        exit();
    }
    else {
        echo "on to the next";
    }
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (!$row = mysqli_fetch_assoc($result)){
        echo "USER IS NOT REGISTERED!";
        header("location:../register_form.php?reset=failure");
        exit();
    } else {
        echo 'Registered user';
    }
    

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail = ?; ";
    
    
    $stmt = mysqli_prepare($conn, $sql);
    if( false === $stmt){
        echo 'ERROR';
        exit();
    }
    else {
        echo 'goinggg!';
    }

    
    mysqli_stmt_bind_param($stmt, "s", $userEmail);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    
    $sql2 = "INSERT INTO pwdReset ( pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?);";
    $stmt2 = mysqli_prepare($conn, $sql2);

    echo 'GOING X2';

    if ( false === $stmt2) {
        echo "There was an error";
        exit();
    }
    else {
        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
        // echo $hashed_token;
        mysqli_stmt_bind_param($stmt2, "ssss", $userEmail, $selector, $hashed_token, $expires);
        mysqli_stmt_execute($stmt2);
    }
    // echo 'Going before 44444';
    // mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);
    mysqli_close($conn);
    
    // echo 'bread';
    
    $mail = new PHPMailer(true);

    echo 'beginning mail!';
    $mail ->isSMTP();
    $mail ->Host = 'smtp.gmail.com';
    $mail ->SMTPAuth = true;
    $mail ->Username = 'elvis.osujic@gmail.com';

    

    $mail ->Password = $_ENV['GMAIL_STMP_PASS']; 
    $mail ->SMTPSecure = 'ssl';
    $mail ->Port = 465;
    $mail ->setFrom('elvis.osujic@gmail.com');

    $mail ->addAddress($userEmail);
    $mail ->isHTML(true);

    
    $mail -> Subject = 'Reset your password for Elvoo app';
    $message = "<p>This is your password link</p>";
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';
    $mail -> Body = $message;
    $headers = "From: Elvoo <elvis.osujic@gmail.com>\r\n";
    $headers = "Reply-To: elvis.osujic@gmail.com\r\n";
    $headers = "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);
    $mail->send();

    header("location:../reset_password.php?reset=success");
}
else {
    header("location:../login_form.php");
};


