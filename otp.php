<?php

session_start();
include_once("config.php");
$imitation = new imitation();
$msg = "";

if(isset($_POST['submit'])) {
    $otp = $_POST['otp']; 
    if($otp == $_SESSION['otp']) {
        $_SESSION['auth'] = '1';
        $checkData = array("email" => $_SESSION['email']);
        $userRes = $imitation->get("users", "*", NULL, $checkData);
        
        if($userRes) {
            $array = array("verified" => "1");
            $updateResult = $imitation->update("users", $array, $checkData);

            if($updateResult) {
                unset($_SESSION['otp']);
                $_SESSION['user_id'] = $userRes[0]['id'];
                header("Location:index.php");
            } else {
                $msg = "<div class='alert alert-danger'>Something went wrong.</div>";    
            }
        } else {
            $msg = "<div class='alert alert-danger'>Not found your account.</div>";    
        }
    } else {
        $msg = "<div class='alert alert-danger'>OTP is invalid.</div>";
    }
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>

<body>
<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START (header-5) -->
    <?php include_once('header.php'); ?>
    <!-- HEADER AREA END -->
    
    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- LOGIN AREA START -->
    <div class="ltn__login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">OTP Verification</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="account-login-inner">
                        <form name="otpFrm" method="POST" id="otpFrm" class="ltn__form-box contact-form-box">
                            <?php echo $msg; ?>
                            <input type="text" name="otp" id="otp" placeholder="OTP*" inputmode="numeric" maxlength="6">
                            <div class="btn-wrapper mt-0">
                                <button class="theme-btn-1 btn btn-block" type="submit" name="submit">Verify</button>
                            </div>
                            <div class="go-to-btn mt-20">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a href="resend-otp.php"><small>Resend OTP?</small></a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- LOGIN AREA END -->

    <!-- FOOTER AREA START -->
    <?php include_once('footer.php'); ?>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add validation rules to the form
            $('#otpFrm').validate({
                rules: {
                    otp: {
                        required: true,
                        digits: true,
                    }
                },
                messages: {
                    otp: {
                        required: "Please enter the 6 digits OTP.",
                        digits: "Please enter only digits.",
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

