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
    }

      // method 'purchaseCoin()'
    public function purchaseCoin($purch_amount, $wallet_addr, $user_id)
    {
        // get the current balance of the wallet
        $wallet_cur_bal = $this->walletRec(2);

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
            $purch_amount_sql = "UPDATE wallet SET wallet_balance = ".(($wallet_cur_bal['wallet_balance']) + (floatval($get_purch_amount)))." ";
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
            } else {
                $errors[] = "Error in Transaction";
            }
        } else {
            $errors[] = "Error in Transaction";
        }
        $errors;
    } // End of if(empty($errors))

        // store messages in array and return the array
        $messages = array('errors' => $errors, 'success' => $success);
        return $messages;
    }
}
?>