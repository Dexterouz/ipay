<?php $menu = 6;?>
<?php
    // include the database connection page
    include 'DBConnection/mysqli_connect.php';
    // declare error/succes array variable
    $errors = $success = array();
    // check if the email and hash exist in the link
    if ((isset($_GET['email'])) && (isset($_GET['hash']))) {
        // store the email and hash in a local variable
        $email = filter_var($_GET['email'], FILTER_SANITIZE_STRING);
        $hash = filter_var($_GET['hash'], FILTER_SANITIZE_STRING);
        $account_status = "active";

        // verify the email, hash and the status of the user in the database
        $verify_sql = "SELECT email_addr, email_confirmation_id, account_status FROM user WHERE ";
        $verify_sql .= "email_addr=? AND email_confirmation_id=?";
        // prepare the statement
        $stmt = $conn->prepare($verify_sql);
        // bind parameters to identifiers
        $stmt->bind_param('ss', $email, $hash);
        // execute the statement
        $stmt->execute();
        // get the result of the execution
        $result = $stmt->get_result();

        // check if the row exist
        if ($result->num_rows == 1) {
            // proceed to update the account status of the user
            $update_sql = "UPDATE user SET account_status=? WHERE email_addr=?";
            // prepare the statement
            $stmt = $conn->prepare($update_sql);
            // bind parameter to identifier
            $stmt->bind_param('ss',$account_status, $email);
            // execute the statement
            $stmt->execute();
            // check if the row has been updated
            if ($stmt->affected_rows == 1) {
                $success[] = "Your email address has been verified";
            } else {
                $errors[] = "This link has already been verified";
            }
        } else {
            $errors[] = "Invalid Link! Please visit the link sent to your email address";
        }
    } else {
        $errors[] = "Invalid Request! Please visit the link sent to your email address";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/report_message.min.css">
    <link rel="stylesheet" href="styles/styles.min.css">
    <title>iPay Email Verfiication Page</title>
</head>
<body>
    <div class="container">
        <section class="content">
        <?php include 'includes/navigation.php'; ?>
            <section class="register">
                <!-- <h1 class="title">Generate Wallet</h1> -->
                <span class="back"><a href="login.php">&larr; Back to Login</a></span>
                <div class="register-container" style="padding: 75px 20px">
                    <!-- For display error -->
                    <?php if(isset($errors)) : ?>
                        <?php foreach($errors as $error) : ?>
                            <span class="error"><?=$error;?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- For displaying success -->
                    <?php if(isset($success)) : ?>
                        <?php foreach($success as $successMsg) : ?>
                            <span class="success"><?=$successMsg;?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                </section>
                <?php include 'includes/footer.html'; ?>
        </section>
    </div>
    <script src="script/main.js"></script>
</body>
</html>