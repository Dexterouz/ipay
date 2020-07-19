<?php 
    // start session
    session_start();
    $menu = 8;
    $user_id = ($_SESSION['user_id']);
    $user_email = ($_SESSION['email']);

    require '../../Wallet.php';
    $wallet = new Wallet();
    $wallet_rec = $wallet->walletRec($user_id);
    $wallet_acct_status = ($wallet_rec['wallet_acct_status']);

    // to keep unauthorize user an access to this page
    if ((!isset($user_id)) && (!isset($user_email))) {
        header("Location: ../../login.php");
        exit();
    }
    // to keep an active user an access
    if ($wallet_acct_status == 'active') {
        header("Location: ../dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/report_message.min.css">
    <link rel="stylesheet" href="../../styles/styles.min.css">
    <title>iPay Generate Wallet Page</title>
</head>
<body>
    <div class="container">
        <section class="content">
        <?php include '../../includes/navigation.php'; ?>
            <section class="register">
                <h1 class="title">Generate Wallet</h1>
                <span class="back"><a href="../dashboard.php">&larr; Back to Dashboard</a></span>
                <div class="register-container">
                <?php 
                if (isset($_POST['generate'])) {
                    $get_messg = $wallet->generateWallet($_POST['wal_usr_name'], $_POST['wal_usr_address'], $_POST['user_id']);
                    // for errors
                    $errors = $get_messg['error'];
                    // for success
                    $success = $get_messg['success'];
                    // for reporting error message(s)
                    if (isset($errors)) {
                        foreach ($errors as $error) {
                            echo '<span class="error">'.$error.'</span>';
                        }
                    }

                    // for reporting success message(s)
                    if (isset($success)) {
                        foreach ($success as $successMsg) {
                            echo '<span class="success">'.$successMsg.'</span>';
                        }
                    }
                }
                ?>
                    <form action="<?=$_SERVER['PHP_SELF'];?>" method="post" onreturn="false">
                        <div class="form-row">
                            <label for="wal_usr_name">Wallet Name:</label>
                            <input type="text" name="wal_usr_name" id="wal_usr_name" placeholder="Enter your wallet username">
                        </div>
                        <div class="form-row">
                            <label for="wal_usr_address">Wallet Address:</label>
                            <input type="text" name="wal_usr_address" value="" style="width: 89%; display:inline;" placeholder="Click on the green button to generate wallet address" id="wal_usr_address" readonly>
                            <button type="button" class="generate-btn" id="generate-btn">Click</button>
                        </div>
                        <input type="hidden" name="user_id" value="<?=$user_id?>">
                        <div class="form-row">
                            <input type="submit" name="generate" id="generate" value="Generate Wallet">
                        </div>
                        <!-- <div class="form-row">
                            <p class="not">Already Registered? Click <a href="login.php">Here</a> to login</p>
                        </div> -->
                    </form>
                </div>
                </section>
                <?php include '../../includes/footer.html'; ?>
        </section>
    </div>
    <script src="../../script/main.js"></script>
</body>
</html>