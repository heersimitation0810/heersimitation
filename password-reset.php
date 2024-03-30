<?php
session_start();
include_once("config.php");
include_once("email.php");
$imitation = new imitation();
$msg = "";

if(!isset($_GET['email'])) {
    header("Location:index.php");
}

if (isset($_POST['submit'])) {
    $condition = array("email" => base64_decode($_GET['email']));
    $result = $imitation->get("users", "*", NULL, $condition);
    
    if (count($result) == 1) {
        $email = $result[0]['email'];
        $array = array(
            "password"   => base64_encode($_POST['password']),
            "updated_at" => date("Y-m-d H:i:s")
        );
    
        $condition = array("email" => $email);
        $query = $imitation->update("users", $array, $condition);
    
        if ($query == 1) {
            $msg = '<div class="alert alert-success"><b>Password Reset Successfully.</b></div>';
        } else {
            $msg = '<div class="alert alert-danger"><b>Something Went Wrong !!</b></div>';
        }
    } else {
        $msg = '<div class="alert alert-danger"><b>Something Went Wrong !!</b></div>';
    }
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>

<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START (header-5) -->
    <?php include_once('header.php'); ?>
    <!-- HEADER AREA END -->
    
    <!-- Utilize Cart Menu Start -->
    
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- LOGIN AREA START (Register) -->
    <div class="ltn__login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area text-center">
                        <h1 class="section-title">Password Reset</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="account-login-inner">
                        <form name="registerFrm" id="registerFrm" method="POST" class="ltn__form-box contact-form-box">
                            <?php echo $msg; ?>
                            <input type="text" name="email" id="email" placeholder="Email*" disabled value="<?php echo base64_decode($_GET['email']); ?>">
                            <input type="password" name="password" id="password" placeholder="Password*">
                            <input type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password*">
                            <div class="btn-wrapper">
                                <button class="theme-btn-1 btn reverse-color btn-block" type="submit" name="submit">Change Password</button>
                            </div>
                            <div class="go-to-btn mt-20">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5><a href="login.php">Login to account</a></h5>
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
            $('#registerFrm').validate({
                rules: {
                    password: {
                        required: true,
                    },
                    confirmpassword: {
                        required: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "Please enter the password"
                    },
                    confirmpassword: {
                        required: "Please enter the confirm password",
                        equalTo: "Passwords do not match"
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:19:46 GMT -->
</html>

