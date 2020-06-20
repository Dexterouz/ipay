<?php 
    // array variable to hold error message(s)
    $errors = array();

    // array variable to hold warrning message(s)
    $warnings = array();

    // validate user email address input
    $email = trim($_POST['email']);
    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address";
        } else {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        }
    } else {
        $errors[] = "Please enter your email address";
    }

    // validate user password input
    $password = trim($_POST['password']);
    if (empty($password)) {
        $errors[] = "Please enter your password";
    } else {
        $password = filter_var($password, FILTER_SANITIZE_STRING);
    }


    // If there's no error; everything is OK
    if (empty($errors)) {
        try {
            // include the database connection file
            include 'DBConnection/mysqli_connect.php';

            // select records from database
            $login_sql = "SELECT user_id, f_name, l_name, email_addr, passwd, account_status "; 
            $login_sql .= "FROM user WHERE email_addr = ?";

            // initialize statment
            $login_stmt = mysqli_stmt_init($conn);
            // prepare the statment for execution
            mysqli_stmt_prepare($login_stmt, $login_sql);
            // bind identifiers to parameter
            mysqli_stmt_bind_param($login_stmt, 's', $email);
            // execute the statment
            mysqli_stmt_execute($login_stmt);
            // get login result 
            $login_result = mysqli_stmt_get_result($login_stmt);
            // fetch the result in an array
            $row = mysqli_fetch_array($login_result, MYSQLI_BOTH);

            // check if email match record in DB
            // if email is true; proceed to verify password
            // if password varification is true; proceed verify account status
            // if account status has been confirm acive; log user in 
            if (mysqli_num_rows($login_result) == 1) {
                // get the hashed password from the user's account
                $password_hash = $row[4];
                if (password_verify($password, $password_hash)) {
                    // verify account status
                    $account_status = $row['account_status'];
                    if ($account_status == "pending") {
                        $warnings[] = "You are yet to confirm your email address, your account is pending";
                    } else {
                        // start session
                        session_start();
                        // store each database record in the session variable
                        $_SESSION['user_id'] = (int) $row[0];
                        $_SESSION['f_name'] = $row[1];
                        $_SESSION['l_name'] = $row[2];
                        $_SESSION['email'] = $row[3];

                        // dashboard url
                        $url = "member_page/dashboard.php";

                        // redirect user to dashboard
                        header("Location: ".$url);
                        exit();
                    }
                } else {
                    // report error
                    $errors[] = "Email/Password do not match our record";
                }
            } else {
                $errors[] = "Email/Password do not match our record";
            }
        } catch (Exception $e) {
            print "An Exception has occurred ".$e->getMessage();
            print "The System is busy, please try again later.";
            date_default_timezone_set('Africa/lagos');
            $date = date('m:d:Y h:i:sa');
            $error_string = $date . " | Login | "."{$e->getMessage()} | "."{$e->getLine()}";
            error_log($error_string, 3, "logs/exception_log.log");
        } catch (Error $e) {
            print "An Error has occurred ".$e->getMessage();
            print "The System is busy, please try again later.";
            date_default_timezone_set('Africa/lagos');
            $date = date('m:d:Y h:i:sa');
            $error_string = $date . " | Login | "."{$e->getMessage()} | "."{$e->getLine()}";
            error_log($error_string, 3, "logs/error_log.log");
        }
    } else {
        // report all error(s)
        return $errors;
    }
?>