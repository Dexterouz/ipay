<?php
    class Wallet
    {
        // declare the properties
        public $wallet_address;
        public $fiat = 10;
        public $coin = 1;
        public $amount; 
        
        // method 'walletBal()'
        public function walletBal($user_id)
        {
            require 'DBConnection/mysqli_connect.php';
            $wallet_sql = "SELECT wallet_balance FROM wallet WHERE user_id=?";
            // create prepare statement
            $wallet_stmt = $conn->prepare($wallet_sql);
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
            $wallet_bal_fiat = ($wallet_bal_coin * $this->fiat);
            // get the plan type
            $plan_type = '';
            if ($wallet_bal_coin <= 10) {
                $plan_type = "junior";
            }
            elseif($wallet_bal_coin <= 20) {
                $plan_type = "marine";
            } elseif ($wallet_bal_coin <= 50) {
                $plan_type = "seal";
            } elseif ($wallet_bal_coin <= 100) {
                $plan_type = "commando";
            } elseif ($wallet_bal_coin <= 2500) {
                $plan_type = "bronze";
            } elseif ($wallet_bal_coin <= 3500) {
                $plan_type = "silver";
            } elseif ($wallet_bal_coin <= 5000) {
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
            $wallet_bal_output = array('bal_coin' => $wallet_bal_coin, 'bal_fiat' => $wallet_bal_fiat);
        }

        return $wallet_bal_output;
    } // end of method walletBal()
}
?>