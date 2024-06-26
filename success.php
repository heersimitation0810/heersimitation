<?php
session_start();
include_once("config.php");
$imitation = new imitation();

if(isset($_POST['type'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    if($_POST['type'] == 'tmpUpdate') {
        $con = array('id' => $_POST['tmpId']);
        $checkPro = $imitation->get('tmp_cart', '*', NULL, $con);

        if(count($checkPro) >= 1) {
            $array = array("qty" => $_POST['qty']);
            $updateResult = $imitation->update("tmp_cart", $array, $con);
            echo 'update';
            exit;
        } 
    }

    if($_POST['type'] == 'remove') {
        $html = '';
        $proCon = array("id" => $_POST['tmpId']);
        $deletePro = $imitation->delete("tmp_cart", $proCon);

        if($deletePro) {
            echo 'success';
        } else {
            echo 'faild';
        }
        exit;
    }
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>
<style>
    .product-info {
    display: flex;
    align-items: center;
}

.product-image {
    margin-right: 20px; /* Adjust as needed */
}

.product-details {
    flex-grow: 1; /* Takes up remaining space */
}

.cart-plus-minus {
    margin-top: 10px; /* Adjust as needed */
}

</style>
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

    <!-- BREADCRUMB AREA START -->
    <!-- BREADCRUMB AREA END -->

    <!-- SHOPING CART AREA START -->
    <div class="liton__shoping-cart-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping-cart-inner">
                        <div class="shoping-cart-table table-responsive">
                            <div class="ltn__checkout-single-content-info mt-20">
                                <div class="row" id="showCart">
                                    <h4><i class="fa fa-check-circle" style="color:green;"></i> Your order is successfully confirm.</h4>
                                    <span>Thank you for purchase in Heers Imitation Jewellery House.</span>
                                </div>
                                <div class="btn-wrapper">
                                    <a href="order-details.php" class="theme-btn-1 btn btn-effect-1">View Order Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- SHOPING CART AREA END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <!-- FEATURE AREA END -->

    <!-- FOOTER AREA START -->
    <?php include_once('footer.php'); ?>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
<script>
    $(document).ready(function(){
        $('.inc').click(function(){
            var tmp_id = $(this).data('tmpid');
            var qtyInput = $('#qty_' + tmp_id);
            var qty = parseInt(qtyInput.val());
            qtyInput.val(qty);

            $.ajax({
                url: 'cart.php',
                method: 'POST',
                data: {
                    type: "tmpUpdate",
                    tmpId: tmp_id,
                    qty: qty
                },
                success: function(response){
                    console.log(response);
                    // $('.product-detail').html(response);
                }
            });
        });

        // Minus button click event
        $('.dec').click(function(){
            var tmp_id = $(this).data('tmpid');
            var qtyInput = $('#qty_' + tmp_id);
            var qty = parseInt(qtyInput.val());
            if(qty > 1) {
                qtyInput.val(qty);
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {
                        type: "tmpUpdate",
                        tmpId: tmp_id,
                        qty: qty
                    },
                    success: function(response){
                        console.log(response);
                        // $('.product-detail').html(response);
                    }
                });
            }
        });

        $('.cart-plus-minus-box').on('input', function(){
            var tmp_id = $(this).data('tmpid');
            var qty = parseInt($(this).val());

            if(qty >= 1) {
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {
                        type: "tmpUpdate",
                        tmpId: tmp_id,
                        qty: qty
                    },
                    success: function(response){
                        console.log(response);
                        // $('.product-detail').html(response);
                    }
                });
            }
        });

        $(document).on('click', '.remove-cart', function() {
            var tmp_id = $(this).data('tmpid');
            $.ajax({
                url: 'cart.php',
                method: 'POST',
                data: {
                    type: "remove",
                    tmpId: tmp_id,
                },
                success: function(response){
                    if(response == 'success') {
                        window.location.reload();
                    }
                }
            });
        });
    });

</script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
</html>

