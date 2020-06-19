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
            error_log($errorstring, 3, "logs/error_log.log");
            } else {
                $date = date('m:d:Y h:i:sa');
                $errorstring = $date." | Error in sending transaction report |";
                error_log($errorstring, 3, "logs/error_log.log");
            }
        } else {
            $date = date('m:d:Y h:i:sa');
            $errorstring = $date." | Error in sending transaction report |";
            error_log($errorstring, 3, "logs/error_log.log");
        }

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
                $success[] = "Your purchase of {$get_purch_amount} coin is successful";

                $report_message = "You purchased {$get_purch_amount} coin on ".date('m:d:Y h:i:sa');
                // send the report of the transaction to the transaction report table
                $this->sendTransactionReport($report_message, $wallet_rec['wallet_id'], $user_id);
            } else {
                $errors[] = "Error in Transaction";
            }
        } else {
            $errors[] = "Error in Transaction";
        }
        $errors;
        // close database connection
        $stmt->close();
    } // End of if(empty($errors))

        // store messages in array and return the array
        $messages = array('errors' => $errors, 'success' => $success);
        return $messages;
    }
}
?>