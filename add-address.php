<?php 
session_start();
include_once("config.php");
$imitation = new imitation();
$msg = '';

if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    $_SESSION['page'] = 'checkout.php';
    header("Location:login.php");
}

if(isset($_POST['submit'])) {
    $id = $_SESSION['user_id'];

    $defaultAddCondition = array("user_id" => $id, 'default_status' => '1');
    $res = $imitation->get('address', 'id', NULL, $defaultAddCondition);
    $defaultAddCondition = array('id' => $res[0]['id']);
    $arr = array('default_status' => '0');
    $defaultAddResult = $imitation->update('address', $arr, $defaultAddCondition);

    $array = array(
        "user_id"        => $id,
        "address_line1"  => isset($_POST['address_line1']) ? $_POST['address_line1'] : null, 
        "address_line2"  => isset($_POST['address_line2']) ? $_POST['address_line2'] : null,
        "city"           => isset($_POST['city']) ? $_POST['city'] : null,
        "state"          => isset($_POST['state']) ? $_POST['state'] : null,
        "country"        => isset($_POST['country']) ? $_POST['country'] : null,
        "zipcode"        => isset($_POST['zipcode']) ? $_POST['zipcode'] : null,
        "default_status" => "1",
        "created_at"     => date("Y-m-d H:i:s")
    );
    $result = $imitation->insert("address", $array);
    
    if ($result == 1) {
        header("Location:checkout.php");
    } else {
        $msg = '<div style="color:red;">Something Went Wrong !!</div>';
    }

}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
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
    
    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- WISHLIST AREA START -->
    <div class="ltn__checkout-area mb-105">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content">
                            <h4 class="title-2">Address Details</h4>
                            <div class="ltn__checkout-single-content-info">
                                <form method="POST" name="addressFrm" id="addressFrm">
                                    <div class="row">
                                        <?php echo $msg; ?>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Address Line1</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="address_line1" placeholder="House number and street name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Address Line2</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="address_line2" placeholder="Apartment, suite, unit etc. (optional)">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6>City</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="city" placeholder="City">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6>State</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="state" placeholder="State">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <h6>Country</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="country" placeholder="Country">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h6>Zipcode</h6>
                                                    <div class="input-item">
                                                        <input type="text" name="zipcode" placeholder="Zipcode" inputmode="numeric" maxlength="6">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn theme-btn-1 btn-effect-1" type="submit" name="submit">Add New Address</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- WISHLIST AREA START -->

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
            $('#addressFrm').validate({
                rules: {
                    address_line1: {
                        required: true,
                    },
                    address_line2: {
                        required: true,
                    },
                    city: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    country: {
                        required: true,
                    },
                    zipcode: {
                        required: true,
                        digits: true,
                    }
                },
                messages: {
                    address_line1: {
                        required: "Please enter the address line 1",
                    },
                    address_line2: {
                        required: "Please enter the address line 2",
                    },
                    city: {
                        required: "Please enter the city",
                    },
                    state: {
                        required: "Please enter the state",
                    },
                    country: {
                        required: "Please enter the country",
                    },
                    zipcode: {
                        required: "Please enter the zipcode",
                        digits: "Please enter the only digits",
                    }
                }
            });
        });
    </script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:17 GMT -->
</html>

