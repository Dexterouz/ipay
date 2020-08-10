<?php
    session_start();
    // session_destroy();
    $user_id = ($_SESSION['user_id']);
    $user_f_name = ($_SESSION['f_name']);
    $user_l_name = ($_SESSION['l_name']);
    $user_email = ($_SESSION['email']);

    // if user_id and email is not set
    // redirect user to login page
    if ((!isset($user_id)) && (!isset($user_email))) {
        header("Location: ../login.php");
        exit();
    }
    // menu number for navigation
    $menu = 7;
    // include the wallet class
    include '../Wallet.php';
    // create an object of the wallet class
    $wallet = new Wallet();
    // get the wallet balance 
    $wallet_bal = $wallet->walletBal($user_id);

    // get wallet record(s)
    $wallet_rec = $wallet->walletRec($user_id);
    // store the wallet account status in session
    $_SESSION['wallet_acct_status'] = $wallet_rec['wallet_acct_status'];
    // get the user referral id
    $get_ref_link = $wallet->getUserRec($user_id);
    $user_ref_link = $get_ref_link['referrer_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/report_message.min.css">
    <link rel="stylesheet" href="../styles/styles.min.css">
    <title>Dashboard::<?=ucwords($user_f_name);?></title>
</head>
<body>
    <div class="container">
        <section class="content">
            <?php include '../includes/navigation.php'; ?>
                <section class="dashboard">
                    <!-- Welcome title -->
                    <?php if(($wallet_rec['wallet_acct_status']) !== "active") : ?>
                        <span class="info">Welcome to your dashboard! <b><?=ucwords($user_f_name);?></b>, Before you can process any transaction, you need to activate your wallet by registering. Currently your wallet is inactive.</span>
                    <?php endif; ?>
                    <div class="welcome-header">
                        <span class="welcome">Welcome: <b class="user-name"><?=ucwords($user_f_name)." ".($user_l_name);?></b></span>
                        <?php $get_report = $wallet->retrieveTransReport($wallet_rec['wallet_id']);?>
                        <?php $i = 0; if(isset($get_report)){foreach($get_report as $report) { $i++; }}?>
                        <span class="envelope">&#9993; <span class="new-mail"><sup><?=$i;?></sup></span></span>
                        <span class="user-email">Email: <b><?=$user_email;?></b></span>
                    </div>

                    <!-- Account data info -->
                    <div class="data-info">
                        <!-- Coin -->
                        <div class="wallet-info">
                            <div class="currency">
                                <span class="amount"><?=(isset($wallet_bal['bal_coin'])) ? $wallet_bal['bal_coin'] : '0';?></span>
                                <img src="../images/coin.png" alt="Coin">
                            </div>
                            <div class="info-desc">Amount in Coin</div>
                        </div>
                        <!-- Naira -->
                        <div class="wallet-info">
                            <div class="currency">
                                <span class="amount">&#8358;<?=$wallet_bal['bal_fiat'];?></span>
                                <img src="../images/naira.png" alt="Naira">
                            </div>
                            <div class="info-desc">Amount in Naira</div>
                        </div>
                        <!-- Plan type -->
                        <div class="wallet-info">
                            <div class="currency">
                                <span class="amount"><?=ucfirst($wallet_bal['plan_type']);?></span>
                                <img src="../plan-images/<?=$wallet_bal['plan_type'];?>.png" alt="Plan Type">
                            </div>
                            <div class="info-desc">Plan Type</div>
                        </div>
                    </div>
                    
                    <!-- For Transaction -->
                    <fieldset>
                        <legend>Transaction</legend>
                        <div class="transaction-container">
                            <!-- Purchasing Coin -->
                            <div class="transaction">
                                <p class="transaction-title">Purchase Coin</p>
                                <?php
                                    if (isset($_POST['purchase'])) {
                                        if (($_SESSION['wallet_acct_status']) !== "active") {
                                            echo '<script>alert("Your wallet is inactive! Activate your account to make a transaction")</script>';
                                        } else {
                                            $purchase_coin = $wallet->purchaseCoin($_POST['pur_amt_coin'], $_POST['wallet-addr'], $user_id);
                                            $errors = $purchase_coin['errors'];
                                            $success = $purchase_coin['success'];
                                            // for error
                                            if (isset($errors)) {
                                                foreach ($errors as $error) {
                                                    echo "<script>
                                                        alert('".$error."');
                                                        window.location.href='".$_SERVER['PHP_SELF']."';
                                                    </script>";
                                                }
                                            }
                                            // for success
                                            if (isset($success)) {
                                                foreach ($success as $successMsg) {
                                                    echo "<script>
                                                    alert('".$successMsg."');
                                                    window.location.href='".$_SERVER['PHP_SELF']."';
                                                </script>";                                            }
                                            }
                                        }
                                    }
                                ?>
                                <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                                    <!-- Group 1 -->
                                    <div class="form-group">
                                        <!-- Amount Naira -->
                                        <div class="form-row">
                                            <div class="row">
                                                <label for="amount">Amount (DXcoin)</label>
                                                <input type="number" name="pur_amt_coin" id="pur_amt_coin" placeholder="Enter amount in (DXcoin)">
                                            </div>
                                            <div class="row">
                                                <!-- Amount Coin -->
                                                <label for="amount">Amount (NGN)</label>
                                                <input type="number" name="pur_amt_naira" id="pur_amt_naira" placeholder="Enter amount in (NGN)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Group 2 -->
                                    <div class="form-group">
                                        <label for="wallet-addr">Wallet Address</label>
                                        <input type="text" name="wallet-addr" class="purchase" value="<?=$wallet_rec['wallet_addr'];?>" readonly id="wallet-addr">
                                    </div>

                                    <!-- Group 3 -->
                                    <div class="form-group">
                                        <input type="submit" class="purchase" name="purchase" value="Purchase">
                                    </div>
                                </form>
                            </div>

                            <!-- Sending Coin -->
                            <div class="transaction">
                                <p class="transaction-title">Send Coin</p>
                                <?php
                                    if (isset($_POST['send'])) {
                                        if (($_SESSION['wallet_acct_status']) !== "active") {
                                            echo '<script>alert("Your wallet is inactive! Activate your account to make a transaction")</script>';
                                        } else {
                                            $purchase_coin = $wallet->sendCoin($_POST['send_amt_naira'], $_POST['wallet-addr'], $user_id);
                                            $errors = $purchase_coin['errors'];
                                            $success = $purchase_coin['success'];
                                            // for error
                                            if (isset($errors)) {
                                                foreach ($errors as $error) {
                                                    echo "<script>
                                                        alert('".$error."');
                                                        window.location.href='".$_SERVER['PHP_SELF']."';
                                                    </script>";
                                                    echo '<span class="error">'.$error.'</span>';
                                                }
                                            }
                                            // for success
                                            if (isset($success)) {
                                                foreach ($success as $successMsg) {
                                                    echo "<script>
                                                        alert('".$successMsg."');
                                                        window.location.href='".$_SERVER['PHP_SELF']."';
                                                    </script>";
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                                    <div class="form-group">
                                        <!-- Amount Naira -->
                                        <div class="form-row">
                                            <div class="row">
                                                <label for="amount">Amount (NGN)</label>
                                                <input type="number" name="send_amt_naira" id="send_amt_naira" placeholder="Enter amount in (NGN)">
                                            </div>
                                            <div class="row">
                                            <!-- Amount Coin -->
                                                <label for="amount">Amount (DXcoin)</label>
                                                <input type="number" name="send_amt_coin" onfocus="toNaira()" id="send_amt_coin" placeholder="Enter amount in (DXcoin)">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Group 2 -->
                                    <div class="form-group">
                                        <label for="wallet-addr">Wallet Address</label>
                                        <input type="text" name="wallet-addr" placeholder="Enter receiver wallet address" id="wallet-addr">
                                    </div>
                                    
                                    <!-- Group 3 -->
                                    <div class="form-group">
                                        <input type="submit" class="send" name="send" value="Send">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </fieldset>

                    <!-- For Wallet Action -->
                    <fieldset>
                        <legend>Wallet Action</legend>
                        <div class="wallet-container">
                            <!-- Wallet status -->
                            <div class="wallet-action">
                                <?php if(($_SESSION['wallet_acct_status']) == 'active') :?>
                                    <p class="action-title">Wallet Status: <span class="active">Active</span></p>
                                    <div class="btn-action">
                                        <button type="button" class="disable" disabled onclick="location.href='./setting/generate-wallet.php';">Generate Wallet</button>
                                    </div>
                                <?php else: ?>
                                <p class="action-title">Wallet Status: <span class="pending">Pending</span></p>
                                <div class="btn-action">
                                    <button type="button" onclick="location.href='./setting/generate-wallet.php';">Generate Wallet</button>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Claim bonus -->
                            <div class="wallet-action">
                            <p class="action-title">Claim Registeration Bonus</p>
                                <div class="btn-action">
                                <?php
                                if (array_key_exists('bonus',$_POST)) {
                                    $bonus = $wallet->claimBonus($user_id);
                                    $errors = $bonus['error'];
                                    $success = $bonus['success'];
                                    // for errors
                                    if (isset($errors)) {
                                        foreach ($errors as $error) {
                                            echo "<script>alert('".$error."');</script>";
                                        }
                                    }
                                    // for success
                                    if (isset($success)) {
                                        foreach ($success as $successMsg) {
                                            echo "<script>
                                                    alert('".$successMsg."');
                                                    window.location.href='".$_SERVER['PHP_SELF']."';
                                                </script>";
                                        }
                                    }
                                }
                                ?>
                                <form method="post">
                                    <button type="submit" name="bonus"<?=($wallet_rec['bonus_status'] == 'yes') ? 'disabled class="disable"' : ''; ?> value="bonus1">Claim Bonus</button>
                                </form>
                                </div>
                            </div>

                            <!-- View wallet address -->
                            <div class="wallet-action">
                                <p class="action-title">View your wallet address</p>
                                <div class="btn-action">
                                    <button type="button" id="view_addr_btn" onclick="display();">View address</button>
                                </div>
                                <span id="disp_addr"><?=$wallet_rec['wallet_addr'];?></span>
                            </div>
                        </div>
                    </fieldset>
                    
                    <!-- for referral link -->
                    <fieldset>
                        <legend>Referral Link</legend>
                        <label for="ref_link">Referral Link:</label>
                        <input type="text" readonly name="ref_link" id="ref_link" <?="value='localhost/ipay/register.php?referral=$user_ref_link'"?>>
                        <input type="button" value="Copy" id="copy-btn" onclick="copy()">
                    </fieldset>

                    <!-- For mail -->
                    <fieldset>
                        <legend>Wallet Transaction Report</legend>
                        <div class="mail-container">
                            <?php if (isset($_GET['delete'])) {
                                $delete_report = htmlspecialchars($_GET['delete'], ENT_QUOTES);
                                $delete_status = $wallet->deleteTransReport($delete_report);
                                // if report delete is successful, display success message
                                if (isset($delete_status['success'])) {
                                    foreach ($delete_status['success'] as $success) {
                                        echo "<script>
                                                alert('".$success."');
                                                window.location.href='".$_SERVER['PHP_SELF']."';
                                            </script>";
                                    }
                                }

                                // if report delete is not successful, display error message
                                if (isset($delete_status['errors'])) {
                                    foreach ($$delete_status['error'] as $error) {
                                        "<script>
                                            alert('".$error."');
                                            </script>";
                                    }
                                }
                            } ?>
                            <?php $get_report = $wallet->retrieveTransReport($wallet_rec['wallet_id']);?>
                            <details>
                                <?php $i = 0; if(isset($get_report)){foreach($get_report as $report) { $i++; }}?>
                                <summary style="cursor: pointer;">Messages <sup class="mail-count"><?=$i;?></sup></summary>
                                <?php if(isset($get_report)) :?>
                                <?php foreach($get_report as $report) : ?>
                                <p><?=$report['wallet_mails'];?><button type="button" onclick="location.href='<?=$_SERVER['PHP_SELF'];?>?delete=<?=$report['wallet_mail_id'];?>'">&times;</button></p>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </details>
                        </div>
                    </fieldset>
                </section>
            <?php include '../includes/footer.html'; ?>
        </section>
    </div>
    <script src="../script/main.js"></script>
</body>
</html>