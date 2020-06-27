<?php
    class Wallet
    {
        // declare the properties
        public $connect;
        public $wallet_address;
        public $fiat = 10;
        public $coin = 1;
        public $amount; 

        public function __construct()
        {
            $conn = new mysqli("localhost", "root", "", "ipay_db");
            $this->connect = $conn;
        }

        // method 'walletBal()'
        public function walletBal($user_id)
        {
            // require 'DBConnection/mysqli_connect.php';
            $wallet_sql = "SELECT wallet_balance FROM wallet WHERE user_id=?";
            // create prepare statement
            $wallet_stmt = $this->connect->prepare($wallet_sql);
            if (isset($wallet_stmt)) {
            // bind parameters to identifiers
            $wallet_stmt->bind_param('i', $user_id);
            // execute query
            $wallet_stmt->execute();
            // fetch the result of the query
            $result = $wallet_stmt->get_result();
            // fetch result in associative array
            $row = $result->fetch_assoc();
            // balance in coin
            $wallet_bal_coin = $row['wallet_balance'];
            // equivalent in Naira; 1coin = 10 naira
            $wallet_bal_fiat = (floatval($wallet_bal_coin) * $this->fiat);
            // get the plan type
            $plan_type = '';
            if ($wallet_bal_coin <= 19) {
                $plan_type = "junior";
            }
            elseif($wallet_bal_coin <= 49) {
                $plan_type = "marine";
            } elseif ($wallet_bal_coin <= 99) {
                $plan_type = "seal";
            } elseif ($wallet_bal_coin <= 249) {
                $plan_type = "commando";
            } elseif ($wallet_bal_coin <= 349) {
                $plan_type = "bronze";
            } elseif ($wallet_bal_coin <= 499) {
                $plan_type = "silver";
            } elseif ($wallet_bal_coin <= 1999) {
                $plan_type = "Gold";
            } else {
                $plan_type = "legend";
            }
            // store the out in array
            $wallet_bal_output = array(
                'bal_coin' => $wallet_bal_coin, 
                'bal_fiat' => $wallet_bal_fiat,
                'plan_type' => $plan_type
            );
            } else {
            // if in the event the wallet is not active
            // or not created yet; display zero balance
            $wallet_bal_coin = 0; // balance in coin
            $wallet_bal_fiat = ($wallet_bal_coin * $this->fiat); // equivalent in Naira; 1coin = 10 naira

            // store the out in array
            $wallet_bal_output = array(
                'bal_coin' => $wallet_bal_coin,
                'bal_fiat' => $wallet_bal_fiat,
                'plan_type' => 'none'
            );
        }

        return $wallet_bal_output;

        // close connection to database
        $wallet_stmt->close();
    } // end of method walletBal()

    // method retrieve walletAddr()
    public function walletRec($user_id)
    {
        // retrieve the wallet adress
        $wallet_sql = "SELECT * FROM wallet WHERE user_id=?";
        $wallet_stmt = $this->connect->prepare($wallet_sql);

        if (isset($wallet_stmt)) {
            $wallet_stmt->bind_param('i', $user_id);
            $wallet_stmt->execute();
            $result = $wallet_stmt->get_result();
            $wallet_row = $result->fetch_assoc();
        } else {
            $wallet_row = '';
        }

        
        return $wallet_row;
        // close connection to database
        $wallet_stmt->close();
    }

    // method 'sendTransactionReport()'
    public function sendTransactionReport($wallet_report, $wallet_id, $user_id)
    {
        // get the user wallet id
        $get_wallet_id = $this->walletRec($user_id);
        // write the query for inserting report
        $wallet_report_sql = "INSERT INTO wallet_mail (wallet_mails, wallet_id, user_id) VALUES ";
        $wallet_report_sql .= "(?, ?, ?)";
        // prepare the statement
        $stmt = $this->connect->prepare($wallet_report_sql);
        if (isset($stmt)) {
            // bind parameter to identifier
        $stmt->bind_param('sii', $wallet_report, $wallet_id, $user_id);
        // execute the statement
        $stmt->execute();

        // set the date time zone
        date_default_timezone_set('Africa/lagos');

        // if the report has been inserted
        // send logs to error log
        if ($stmt->affected_rows == 1) {
            $date = date('m:d:Y h:i:sa');
            $errorstring = $date." | transaction report sent |";
            // error_log($errorstring, 3, "logs/error_log.log");
            } else {
                $date = date('m:d:Y h:i:sa');
                $errorstring = $date." | Error in sending transaction report |";
                // error_log($errorstring, 3, "./logs/error_log.log");
            }
        } else {
            $date = date('m:d:Y h:i:sa');
            $errorstring = $date." | Error in sending transaction report |";
            // error_log($errorstring, 3, "logs/error_log.log");
        }

        // close connection to database
        $stmt->close();
    }

    // method 'receiver_addr()'
    function receiver_rec($recvr_addr)
    {
        $recvr_addr_sql = "SELECT * FROM wallet WHERE wallet_addr=?";
        // prepare the statment
        $stmt = $this->connect->prepare($recvr_addr_sql);
        if (isset($stmt)) {
            // bind parameter to identifier
            $stmt->bind_param('s', $recvr_addr);
            // execute the statement
            $stmt->execute();
            // get the result of the excution
            $result = $stmt->get_result();
            // fetch the result in an array
            $result_row = $result->fetch_assoc();
        }
        // return the result
        return $result_row;
        // close connection to database
        $stmt->close();
    }

      // method 'purchaseCoin()'
    public function purchaseCoin($purch_amount, $wallet_addr, $user_id)
    {
        // get the current balance of the wallet
        $wallet_rec = $this->walletRec($user_id);

        // declare report message variable
        $errors = array(); # for error message(s)
        $success = array(); # for success message(s)

        // validate amount
        $get_purch_amount = htmlspecialchars($purch_amount, ENT_QUOTES);
        if (empty($get_purch_amount)) {
            $errors[] = "Enter amount to purchase";
        }

        // validate wallet address
        $get_wallet_addr = filter_var($wallet_addr, FILTER_SANITIZE_STRING);
        if (empty($get_wallet_addr)) {
            $errors[] = "Wallet address is empty";
        }

        // if there exist no error
        // update the current balance
        // with the requested purchase amount
        if (empty($errors)) {
            $purch_amount_sql = "UPDATE wallet SET wallet_balance = ".(($wallet_rec['wallet_balance']) + (floatval($get_purch_amount)))." ";
            $purch_amount_sql .= "WHERE wallet_addr=? AND user_id=?";
            // prepare the statement
            $stmt = $this->connect->prepare($purch_amount_sql);
            if (isset($stmt)) {
                // bind the parameters to the identifiers
            $stmt->bind_param('si',$get_wallet_addr, $user_id);
            // execute the statement
            $stmt->execute();
            // get the result of the statement execution
            $result = $stmt->get_result();
            if ($stmt->affected_rows == 1) {
                $success[] = "Your purchase of {$get_purch_amount} DXcoin was successful";

                $report_message = "You purchased {$get_purch_amount} DXcoin on ".date('m:d:Y h:i:sa').", your new balance is ".(($wallet_rec['wallet_balance']) + (floatval($get_purch_amount)))." DXcoin";
                // send the report of the transaction to the transaction report table
                $this->sendTransactionReport($report_message, $wallet_rec['wallet_id'], $user_id);
            } else {
                $errors[] = "Error in Transaction";
            }
        } // End of if(empty($errors))   

        // close database connection
        $stmt->close();
    } 
    // store messages in array and return the array
    $messages = array('errors' => $errors, 'success' => $success);
    return $messages;
}

    // method 'sendCoin()'
    public function sendCoin($sendr_amount, $receiver_addr, $sendr_user_id)
    {
        date_default_timezone_set("Africa/lagos");
        // get sender wallet balance
        $get_sendr_wal_rec = $this->walletRec($sendr_user_id);
        // get sender wallet id
        $get_sendr_wal_id = $get_sendr_wal_rec['wallet_id'];
        // get sender wallet balance
        $get_sendr_wal_bal = $get_sendr_wal_rec['wallet_balance'];
        // get sender user name
        $get_sendr_wal_name = $get_sendr_wal_rec['wallet_name'];

        // get receiver's wallet record
        $get_recvr_wal_rec = $this->receiver_rec($receiver_addr);

        // get receiver user id
        $get_recvr_wal_usrid = $get_recvr_wal_rec['user_id'];
        // get receiver wallet id
        $get_recvr_wal_id = $get_recvr_wal_rec['wallet_id'];
        // get receiver wallet balance
        $get_recvr_wal_bal = $get_recvr_wal_rec['wallet_balance'];
        // get receiver's user name
        $get_recvr_uname = $get_recvr_wal_rec['wallet_name'];

        // array variable to store error message(s)
        $errors = array();
        // array variable to store success message(s)
        $success = array();
        
        // validate sender amount input
        $get_sendr_amount = htmlspecialchars($sendr_amount, ENT_QUOTES);
        if (empty($get_sendr_amount)) {
            $errors[] = "Enter amount";
        }

        // validate the receiver's address
        $get_recvr_addr = htmlspecialchars($receiver_addr, ENT_QUOTES);
        if (empty($get_recvr_addr)) {
            $errors[] = "Enter receiver wallet address";
        }

        if (empty($errors)) {
            // check if the sending amount is greater than
            // the sender's wallet account balance
            if (($get_sendr_wal_bal) <= ($get_sendr_amount)) {
                $errors[] = "You have an insufficient balance to make this transaction";
            } else {
                // proceed to update the receiver's wallet account balance
                $recvr_wal_bal_sql = "UPDATE wallet SET wallet_balance = ".(($get_recvr_wal_bal) + ($get_sendr_amount))." ";
                $recvr_wal_bal_sql .= "WHERE wallet_addr=?";
                // prepare the statement
                $stmt = $this->connect->prepare($recvr_wal_bal_sql);
                // if the statement has been set
                if (isset($stmt)) {
                    // bind parameter to identifier
                    $stmt->bind_param('s', $get_recvr_addr);
                    // execute the statement
                    $stmt->execute();

                    // if the update is successful
                    // print success message; else print error message
                    if ($stmt->affected_rows == 1) {
                        // display transfer success message
                        $success[] = "Transfer of {$get_sendr_amount} DXcoin to {$get_recvr_uname} was successful";

                        $report_message = "You received {$get_sendr_amount} DXcoin from {$get_sendr_wal_name} on ".date('m:d:Y h:i:sa').", your new balance is ".(($get_recvr_wal_bal) + ($get_sendr_amount))." DXcoin";
                        // send the report of the transaction to the transaction report table
                        $this->sendTransactionReport($report_message, $get_recvr_wal_id, $get_recvr_wal_usrid);

                        // proceed to update the sender's wallet account balance
                        $sendr_wal_bal_sql = "UPDATE wallet SET wallet_balance = ".(($get_sendr_wal_bal) - ($get_sendr_amount))." ";
                        $sendr_wal_bal_sql .= "WHERE user_id=?";
                        // prepare the statement
                        $stmt = $this->connect->prepare($sendr_wal_bal_sql);
                        // check if statement is set
                        if (isset($stmt)) {
                            // bind parameter to identifier
                            $stmt->bind_param('i', $sendr_user_id);
                            // execute the statement
                            $stmt->execute();
                            // check if row has been affected
                            if ($stmt->affected_rows) {
                                $report_message = "You transfered {$get_sendr_amount} DXcoin to {$get_recvr_uname} on ".date('m:d:Y h:i:sa').", your new balance is ".(($get_sendr_wal_bal) - ($get_sendr_amount))." DXcoin";
                                // send the report of the transaction to the transaction report table
                                $this->sendTransactionReport($report_message, $get_sendr_wal_id, $sendr_user_id);
                            }
                        } else {
                            $errors[] = "Transfer failed!";
                        }
                    } else {
                        $errors[] = "Transfer failed!, receiver address is invalid";
                    }
                } else {
                    $errors[] = "Transfer failed!";
                }
                // close connection to database
                $stmt->close();
            }
        } # end of if(empty($errors))

        // store messages in array and return the array
        $messages = array('errors' => $errors, 'success' => $success);
        return $messages;
    }

    // method 'generateWallet()'
    public function generateWallet($user_name, $user_addr, $user_id) {
        // variable to hold error(s)
        $errors = array();
        $success = array();

        // validate the user input from the form

        // validate first name
        $wal_usr_name = trim($user_name);
        if (empty($wal_usr_name)) {
            $errors[] = "Please specify your wallet user name";
        } else {
            $wal_usr_name = (filter_var($wal_usr_name, FILTER_SANITIZE_STRING));
        }

        // validate last name
        $wal_usr_address = trim($user_addr);
        if (empty($wal_usr_address)) {
            $errors[] = "Please generate your wallet address by clicking the green button";
        } else {
            $wal_usr_address = (filter_var($wal_usr_address, FILTER_SANITIZE_STRING));
        }

        // set wallet accont status default as pending
        $account_status = "active";

        // set registeration bonus
        $initial_balance = 0.0;

        // if everything is OK
        if (empty($errors)) {
            // proceed to insert record in to database
            try {
                // insert query
                $wallet_sql = "INSERT INTO wallet (wallet_name, wallet_addr, wallet_balance, wallet_acct_status, user_id) ";
                $wallet_sql .= "VALUES (?, ?, ?, ?, ?)";
                // create prepare statement
                $stmt = $this->connect->prepare($wallet_sql);
                // bind parameters to identifier
                $stmt->bind_param("ssisi", $user_name, $user_addr, $initial_balance, $account_status, $user_id);
                // execute the statement
                $stmt->execute();
                // check if record has been inserted
                if ($stmt->affected_rows == 1) {
                    $success[] = "Wallet created successfully!";
                    // generate report message
                    $wallet_rec = $this->walletRec($user_id);
                    date_default_timezone_set('Africa/lagos');
                    // report message string
                    $report_message = "Creation of your wallet account on ".(date('m/d/Y h:i:sa'))." was successfull";
                    $this->sendTransactionReport($report_message, $wallet_rec['wallet_id'], $user_id);
                } else {
                    $errors[] = "Wallet creation failed! due to system error. We will rectify it asap";
                }
                // close connection to database
                $stmt->close();
            } catch (Exception $e) {
                print "An Exception has occurred ".$e->getMessage();
                print "The System is busy, please try again later.";
                date_default_timezone_set('Africa/lagos');
                $date = date('m:d:Y h:i:sa');
                $error_string = $date . " | Wallet creation | "."{$e->getMessage()} | "."{$e->getLine()} | "."{$e->getFile()}\n";
                error_log($error_string, 3, "logs/exception_log.log");  
            } catch (Error $e) {
                print "An Error has occurred ".$e->getMessage();
                print "The System is busy, please try again later.";
                date_default_timezone_set('Africa/lagos');
                $date = date('m:d:Y h:i:sa');
                $error_string = $date . " | Wallet creation | "."{$e->getMessage()} | "."{$e->getLine()} | "."{$e->getFile()}\n";
                error_log($error_string, 3, "logs/error_log.log");
            }
        }
            // store error/success message in assoc array
            $messages = array('error' => $errors, 'success' => $success);
            // return the messages
            return $messages;
    }

    // method 'claimBonus()'
    public function claimBonus($user_id)
    {
        // error/success variable
        $errors = $success = array();
        // get the wallet current balance
        $wallet_rec = $this->walletRec($user_id);
        $wallet_bal = $wallet_rec['wallet_balance'];
        $wallet_id = $wallet_rec['wallet_id'];

        // declare the bonus amount
        $bonus = 10.0;
        // change bonus status
        $bonus_status = 'yes';
        try{
            // write the query to update the user's wallet balance
            $bonus_sql = "UPDATE wallet SET wallet_balance= ".($wallet_bal + $bonus).", bonus_status = '$bonus_status' WHERE user_id=?";
            // prepare the statement
            $stmt = $this->connect->prepare($bonus_sql);
            if (isset($stmt)) {
                // bind parameter to identifier
                $stmt->bind_param('i', $user_id);
                // execute the statement
                $stmt->execute();
                // if row is updated
                if ($stmt->affected_rows == 1) {
                    $success[] = "You have claimed your bonus";
                    date_default_timezone_set('Africa/lagos');
                    // send report of transaction
                    $report_message = "You claimed your registeration bonus of {$bonus} DXcoin on ".(date('m/d/Y h:i:sa')).", your new balance is ".($wallet_bal + $bonus)." Dxcoin";
                    $this->sendTransactionReport($report_message, $wallet_id, $user_id);
                } else {
                    $errors[] = "You wallet is pending";
            }
        }
    } catch (Exception $e) {
        print "An Exception has occured: ".$e->getMessage();
        date_default_timezone_set('Africa/lagos');
        $date = date('m:d:Y h:i:sa');
        $error_string = $date . " | Claim Bonus Exception | {$e->getMessage()} | {$e->getLine()}\n";
        error_log($error_string, 3, "logs/exception_log.log");
    } catch (Error $e) {
        print "An Error has occured ".$e->getMessage();
        date_default_timezone_set('Africa/lagos');
        $date = date('m:d:Y h:i:sa');
        $error_string = $date . " | Claim Bonus Error | {$e->getMessage()} | {$e->getLine()}\n";
        error_log($error_string, 3, "logs/error_log.log");
    }
        // close connection to database
        // $stmt->close();

        // return the messages
        $messages = array(
            'error' => $errors,
            'success' => $success 
            );
        return $messages;
}

    // method 'wallet_mail_rec()'
    public function wallet_mail_rec($wallet_id) {
        // retrieve the wallet adress
        $wallet_sql = "SELECT * FROM wallet WHERE user_id=?";
        $wallet_stmt = $this->connect->prepare($wallet_sql);

        if (isset($wallet_stmt)) {
            $wallet_stmt->bind_param('i', $user_id);
            $wallet_stmt->execute();
            $result = $wallet_stmt->get_result();
            $wallet_row = $result->fetch_assoc();
        } else {
            $wallet_row = '';
        }

        
        return $wallet_row;
        // close connection to database
        $wallet_stmt->close();
    }

    // method 'retrieveTransactionReport()'
    public function retrieveTransReport($wallet_id)
    {
        // report retrieving query
        $retrieve_sql = "SELECT wallet_mail_id, wallet_mails, wallet_id FROM wallet_mail WHERE wallet_id=? ORDER BY wallet_mail_id DESC";
        // prepare the statement
        $stmt = $this->connect->prepare($retrieve_sql);
        if (isset($stmt)) {
            // bind parameter to identifier
            $stmt->bind_param('i', $wallet_id);
            // execute the statement
            $stmt->execute();
            // get the result of the excution
            $result = $stmt->get_result();
            // fetch the result in an associative array
            while($row_result = $result->fetch_assoc()) {
                $row[] = $row_result;
            }
        }
        if (isset($row)) {
            // return result
            return $row;
        }

        // close connection to database
        $stmt->close();
    }

    // method 'retrieveTransactionReport()'
    public function deleteTransReport($wallet_mail_id)
    {
        // delete report messages
        $errors = $success = array();

        // report retrieving query
        $delete_sql = "DELETE FROM wallet_mail WHERE wallet_mail_id=?";
        // prepare the statement
        $stmt = $this->connect->prepare($delete_sql);
        if (isset($stmt)) {
            // bind parameter to identifier
            $stmt->bind_param('i', $wallet_mail_id);
            // execute the statement
            $stmt->execute();
            if ($stmt->affected_rows == 1) {
                $success[] = "Report deleted!";
            } else {
                $errors[] = "Report could not be deleted";
            }
        } else {
            $errors[] = "Error in deleting report";
        }
        // close connection to database
        $stmt->close();
        // return result
        $messages = array('error' => $errors, 'success' => $success);
        return $messages;
    }
}
?>