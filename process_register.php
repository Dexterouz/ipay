<?php
    // variable to hold error(s)
    $errors = array();
    $success = array();

    // validate the user input from the form

    // validate first name
    $f_name = trim($_POST['f_name']);
    if (empty($f_name)) {
        $errors[] = "Please enter your first name";
    } else {
        $f_name = (filter_var($f_name, FILTER_SANITIZE_STRING));
    }

    // validate last name
    $l_name = trim($_POST['l_name']);
    if (empty($l_name)) {
        $errors[] = "Please enter your last name";
    } else {
        $l_name = (filter_var($l_name, FILTER_SANITIZE_STRING));
    }

    // validate email
    $email = trim($_POST['email']);
    if (empty($email)) {
        $errors[] = "Please enter your email address";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Your email address is invalid";
    } else {
        $email = (filter_var($email, FILTER_SANITIZE_EMAIL));
    }

    // validate password
    $password = trim($_POST['password']);
    $password_1 = trim($_POST['c_password']);
    if (!empty($password)) {
        if ($password != $password_1) {
            $errors[] = "Confirm password does not match";
        } else {
            $password = (filter_var($password, FILTER_SANITIZE_STRING));
        }
    } else {
        $errors[] = "Please enter your password";
    }

    // validate phone number
    $phone_no = trim($_POST['phone_no']);
    if (empty($phone_no)) {
        $errors[] = "Please enter your phone number";
    } else {
        $phone_no = (filter_var($phone_no, FILTER_SANITIZE_STRING));
    }

    // to generate referrer id
    $user_referrer_id = substr(uniqid('ref'),0,20);

    // to generate email confirmation id
    $user_email_conf_id = substr(uniqid(md5($email)),4,60);

    // set accont status default as pending
    $account_status = "pending";


    // if everything is OK and no error
    // process the form to be inserted into DB
    if (empty($errors)) {
        // capture inside a try-catch block
        try {
            // include the database connection page
            include 'DBConnection/mysqli_connect.php';
            // check whether a user is registered
            $check_user_sql = "SELECT * FROM user WHERE email_addr = ?";
            // initialize mysqli statment
            $query_stmt = mysqli_stmt_init($conn);
            // prepare the statement
            mysqli_stmt_prepare($query_stmt,$check_user_sql);
            // bind statement identitfier to parameter
            mysqli_stmt_bind_param($query_stmt,'s',$email);
            // execute the statment
            mysqli_stmt_execute($query_stmt);
            // get the result of the execution
            $result = mysqli_stmt_get_result($query_stmt);
            // if there exist no record, insert records in users table
            if (mysqli_num_rows($result) > 0) {
                $errors[] = "Email already in use";
            } else {
                // first of all hash the password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // query to insert record into user table
                $insert_rec_sql = "INSERT INTO user (f_name, l_name, email_addr, passwd, phone_no, referrer_id,";
                $insert_rec_sql .= "date_reg, email_confirmation_id, account_status) VALUES (?, ?, ?, ?, ?, ?,";
                $insert_rec_sql .= " NOW(), ?, ?)";
                // initialize mysqli statment
                $insert_rec_stmt = mysqli_stmt_init($conn);
                // prepare the statement
                mysqli_stmt_prepare($insert_rec_stmt, $insert_rec_sql);
                // bind statement identifiers to parameter
                mysqli_stmt_bind_param($insert_rec_stmt, 'ssssssss',$f_name, $l_name, $email, $password_hash, $phone_no,
                                        $user_referrer_id, $user_email_conf_id, $account_status);
                // execute the statement
                mysqli_stmt_execute($insert_rec_stmt);

                // check if the rows are affected
                // if true, redirect to message page
                // if false, report error
                if (mysqli_stmt_affected_rows($insert_rec_stmt) == 1) {
                    // report success message
                    $success[] = "<b>Registeration Successful!</b><br/> Please confirm your email by clicking the link on your mailbox";
                    // process the email to be sent to the user
                        $recipient = $email;
                        $subject = 'Email Verification';
                        $message = 'Welcome '.$f_name.' '.$l_name.' to iPay investment platform, please find below for your login credential ';
                        $message .= 'Email: '.$email.'  Password: '.$password;
                        $message .= 'Please click on this link localhost/ipay/verify.php?email='.$email.'&hash='.$user_email_conf_id.' to verify your email address';
                        $message = wordwrap($message, 70, "\r\n");
                        $headers = 'From: admin@ipay.com.ng'."\r\n".'Reply-To: admin@ipay.com.ng'."\r\n";
                        // send mail
                        $send = mail($recipient, $subject, $message, $headers);
                } else {
                    // report error message
                    $errors[] = "<b>Registeration Fail!</br> due to system error, we apologise for the incovenience";
                }
            }
            // Close connection to database
            mysqli_close($conn);
        } catch (Exception $e) {
            print "An Exception has occurred ".$e->getMessage();
            print "The System is busy, please try again later.";
            date_default_timezone_set('Africa/lagos');
            $date = date('m:d:Y h:i:sa');
            $error_string = $date . " | Registeration | "."{$e->getMessage()} | "."{$e->getLine()}";
            error_log($error_string, 3, "logs/exception_log.log");
        } catch (Error $e) {
            print "An Error has occurred ".$e->getMessage();
            print "The System is busy, please try again later.";
            date_default_timezone_set('Africa/lagos');
            $date = date('m:d:Y h:i:sa');
            $error_string = $date . " | Registeration | "."{$e->getMessage()} | "."{$e->getLine()}";
            error_log($error_string, 3, "logs/error_log.log");
        }
    } else {
        // return error messages(s)
        return $errors;
    }
?>