<?php

session_start();
include_once("config.php");
include_once("email.php");
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

if(isset($_POST['type'])) {
    if($_POST['type'] == 'resendotp') {
        $con = array('email' => $_SESSION['email']);
        $userData = $imitation->get('users', '*', NULL, $con);

        if($userData) {
            $otp = rand(100000, 999999);
            $_SESSION['otp']  = $otp;
            $email = $_SESSION['email'];
            $name = $userData[0]['first_name'] . ' ' . $userData[0]['last_name'];
            $html = "Dear $name, <br><br>
                        Thank you for choosing Heers imitation jewellery house. To ensure the security of your account, we require you to verify your email address with the following One-Time Password (OTP):
                        <br><br>
                        OTP: <b>$otp</b>
                        <br><br>
                        Please use the above OTP to complete the verification process. This OTP is valid for a limited time period only and should not be shared with anyone. If you did not request this OTP, please ignore this email.
                        <br><br>
                        Best regards, <br>
                        <b>Heer's Imitation Jewellery House</b>
                        <br><br>
                        <img src='cid:logo' alt='Company Logo' style='height:80%; width:80%;'>";
    
            if(smtp_mailer($email, 'OTP Request', $html)) {
                echo 'success';
            } else {
                echo 'Something went wrong !!!';
            }
        } else {
            echo 'Something went wrong !!!';
        }

        exit;
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
                                        <span id="resendotp"><small>Resend OTP?</small></span>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
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

        $(document).on('click', '#resendotp', function() {
            $.ajax({
                url: 'otp.php',
                method: 'POST',
                data: {
                    type: "resendotp"
                },
                success: function(response){
                    if(response == 'success') {
                        swal({
                              title: 'Successfully sent OTP to your register email',
                              icon: 'success'
                        })
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

