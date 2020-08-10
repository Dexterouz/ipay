<?php $menu = 6; ?>
<?php
    // include the database connection file
    include 'DBConnection/mysqli_connect.php';
    // function to generate random string
    function random_str(
        int $length = 64,
        string $keyspace = '01234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ): string {
            if ($length < 1) {
                throw new RangeException("Length must be a positive integer");
            }
            $pieces = [];
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i=0; $i < $length; $i++) { 
                $pieces[] = $keyspace[random_int(0, $max)];
            }
            return implode('',$pieces);
        }

    $errors = $success = array();
    // if a post request is made
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // validat the email address
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format";
            } 
        } else {
            $errors[] = "Please enter your email address";
        }

        $temp_passwd = random_str(5);

        try {
            if (empty($errors)) {
                $verify_email_sql = "SELECT * FROM user WHERE email_addr = ?";
                // prepare the statment
                $stmt = $conn->prepare($verify_email_sql);
                // bind parameter to identifier
                $stmt->bind_param('s', $email);
                // execute the statement
                $stmt->execute();
                // get the result of the execution
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    // hash the new password 
                    $temp_passwd_hash = password_hash($temp_passwd, PASSWORD_DEFAULT);
                    // update the forgotten password with the temporal password
                    $upd_passwd = "UPDATE user SET passwd = ? WHERE email_addr = ?";
                    // prepare the statement
                    $stmt = $conn->prepare($upd_passwd);
                    // bind parameter to identifier
                    $stmt->bind_param('ss', $temp_passwd_hash, $email);
                    // execute the statement
                    $stmt->execute();
                    // check if the row has been updated
                    if ($stmt->affected_rows == 1) {
                        $recipient = $email;
                        $subject = 'Reset your password';
                        $message = 'Your temporal password is '.$temp_passwd.', please find the change password link below ';
                        $message .= 'in other to change your password. Click on localhost/ipay/change-password.php to change your password';
                        $message = wordwrap($message, 70, "\r\n");
                        $headers = 'From: admin@ipay.com.ng'."\r\n".'Reply-To: admin@ipay.com.ng'."\r\n";
                        // send mail
                        $send = mail($recipient, $subject, $message, $headers);
                        // check if it has been sent
                        if ($send) {
                            // report message
                            $success[] = "A mail has been sent to your email address";
                        } else {
                            $errors[] = "Failed to send mail";
                        }
                    } else {
                        $errors[] = "An error has occured";
                    }
                } else {
                    $success[] = "We will get back to you";
                }
            }
        } catch (Exception $e) {
            print "An Exception has occurred. Message: ".$e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/report_message.min.css">
    <link rel="stylesheet" href="./styles/styles.min.css">
    <title>iPay Forgot Password Page</title>
</head>
<body>
    <div class="container">
        <section class="content">
            <?php include 'includes/navigation.php'; ?>
            <section class="login">
                <h1 class="title">Forgot Password</h1>
                <div class="login-container">
                    <form action="forgot.php" method="post" autocomplete="off">
                    <?php 
                        // for reporting error message(s)
                        if (isset($errors)) {
                            foreach ($errors as $error) {
                                echo '<span class="alert-error">'.$error.'</span>';
                            }
                        }

                        // for reporting warning message(s)
                        if (isset($success)) {
                            foreach ($success as $successMsg) {
                                echo '<span class="alert-success">'.$successMsg.'</span>';
                            }
                        }
                        ?>    
                        <div class="form-row">
                            <label for="email">Email:</label>
                            <input type="email" name="email" placeholder="Enter your email address">
                        </div>
                        <div class="form-row">
                            <input type="submit" value="Submit">
                        </div>
                        <div class="form-row">
                            <p class="not">Not registered? Click <a href="register.php">Here</a> to register</p>
                        </div>
                    </form>
                </div>
            </section>

        <?php include 'includes/footer.html'; ?>
        </section>
    </div>
</body>
</html>