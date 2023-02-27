<?php

if (isset($_POST["reset-request-submit"])) {
    
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = "http://localhost/loginFormPHP/loginphp/create-new-pass.php?selector=" . $selector . "&validator=" . bin2hex($token);

    $expires = date("U") + 1800;

    @include 'config.php';
    $userEmail = $_POST["email"];

    $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error";
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
    }

    $sql = "INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error";
        exit();
    }
    else {
        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ssss", $userEmail, $selector, $hashed_token, $expires);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
    echo "him";

    $to = $userEmail;
    $subject = 'Reset your password for Elvoo app';
    $message = "<p>This is your password link</p>";
    $message .= '<a href="' . $url . '">' . $url . '</a></p>';

    $headers = "From: Elvoo <elvis.osujic@gmail.com>\r\n";
    $headers = "Reply-To: elvis.osujic@gmail.com\r\n";
    $headers = "Content-type: text/html\r\n";

    mail($to, $subject, $message, $headers);
    header("location:../reset_password.php?reset=success");
}
else {
    header("location:../login_form.php");
};


?>