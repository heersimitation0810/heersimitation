<?php
session_start();
include_once("config.php");
$imitation = new imitation();
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
                                <h3>Order ID #0000<?php echo base64_decode($_GET['orderId']) ?></h3>
                                <div class="row" id="showCart">
                                    <h4>Item Details</h4>
                                    <?php 
                                        $user_id = $_SESSION['user_id'];
                                        $orderId = base64_decode($_GET['orderId']);
                                        $select ="order_master.id, product.h_price, order_master.payment_method, order_details.qty, order_details.price, order_details.pro_image, product.name";
                                        $joins = "LEFT JOIN order_details ON order_master.id = order_details.order_id
                                                LEFT JOIN product ON order_details.pro_id = product.id
                                                    WHERE order_master.user_id='$user_id' AND
                                                    order_master.id='$orderId'
                                                    ORDER BY order_master.created_at DESC ";
                                        $product = $imitation->get('order_master', $select, $joins);
                                        
                                        if (count($product) >= 1) {
                                            foreach ($product as $key => $val) { 
                                                $mtClass = ($key == 0) ? '' : 'mt-4';
                                                ?>
                                                <div class="card <?php echo $mtClass; ?>" style="background-color: black; border: 1px solid gold;">
                                                    <div class="card-body">
                                                        <div class="product-info">
                                                            <div class="product-image">
                                                                <img src="img/product/<?php echo $val['pro_image']; ?>" alt="" style="height:100px; width:100px;">
                                                            </div>
                                                            <div class="product-details">
                                                                <h4><?php echo $val['name']; ?></h4>
                                                                <h4>₹ <?php echo $val['h_price']; ?> X <?php echo $val['qty']; ?> = ₹ <?php echo $val['h_price'] * $val['qty']; ?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php  
                                            }
                                        }?>
                            </div>
                            <div class="row mt-4" id="showCart">
                                <h4>Shipping Address</h4>
                                <?php
                                    $select1 ="order_master.created_at as orderDate, order_master.total, address.*";
                                    $joins1 = "LEFT JOIN order_master ON address.id = order_master.address
                                                WHERE order_master.id='$orderId'";
                                    $address = $imitation->get('address', $select1, $joins1);
                                ?> 
                                <span><?php echo $address[0]['address_line1'] . ', ' . $address[0]['address_line2'] . ', ' . $address[0]['city'] . "<br>" . $address[0]['state'] . ', ' . $address[0]['country'] . ', ' . $address[0]['zipcode']; ?></span>
                            </div>
                            <div class="row mt-4" id="showCart">
                                <h2>Total ₹ <?php echo $address[0]['total']; ?>.00</h2>
                            </div>
                            <div class="row mt-4">
                            <a href="invoice.php?id=<?php echo $_GET['orderId']; ?>" class="theme-btn-1 btn btn-effect-1 downloadInvoice" title="Add to Cart" data-proid="1">
                                <i class="fas fa-arrow-down"></i>
                                <span>Download Invoice</span>
                            </a>
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
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/cart.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
</html>

