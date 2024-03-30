<?php 
session_start();
include_once("config.php");
include_once("email.php");
require_once 'vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;
$imitation = new imitation();

if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    $_SESSION['page'] = 'checkout.php';
    header("Location:login.php");
}

if(isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
    $condition = array('id' => $_SESSION['user_id']);
    $userData = $imitation->get('users', '*', NULL, $condition);
}

function sendInvoic($orderId) {
    ob_start();
    include_once 'inv.php';
    $html = ob_get_clean();

    $options = new Options();
    $options->setChroot(__DIR__);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->render();
    $pdf_content = $dompdf->output();

    $file_path = 'pdf/#0000' . $orderId . '_invoice.pdf';
    file_put_contents($file_path, $pdf_content);

    $orderDate = date("Y-m-d H:i:s");
    $sub = "New Order #0000" . $orderId;
    $msg = "Order Number: #0000$orderId
            <br>
            Order Date: $orderDate
            <br><br>
            Best regards, <br>
            <b>Heer Imitation Jewellery House</b>
            <br><br>
            <img src='cid:logo' alt='Company Logo' style='height:80%; width:80%;'>";
            
    sendMail('mitulsoni1311@gmail.com', $sub, $msg, $file_path);
}

if(isset($_POST['type'])) {
    if($_POST['type'] == 'payment') {
        if(isset($_POST['payment_id'])) {
            $user_id = $_SESSION['user_id'];
            $paymentId = $_POST['payment_id']; 
            $tmp_con = array('user_id' => $user_id);
            $tmp_order = $imitation->get('tmp_cart', '*', NULL, $tmp_con);

            if(count($tmp_order) >= 1) {
                $total = 0;
                $select ="tmp_cart.pro_id, tmp_cart.pro_image, tmp_cart.qty, product.id, product.name, product.primary_img, product.h_price";
                $joins = "LEFT JOIN tmp_cart ON tmp_cart.pro_id = product.id
                            WHERE tmp_cart.user_id='$user_id'
                            GROUP BY product.id
                            ORDER BY tmp_cart.created_at DESC ";
                $product = $imitation->get('product', $select, $joins);
        
                if(count($product) >= 1) {
                    foreach($product as $k => $v) { 
                        $tmp = 0;
                        $tmp = $v['qty'] * $v['h_price'];
                        $total += $tmp;
                    }
                }

                $addressCon = array('user_id' => $user_id, 'default_status' => '1');
                $addressData = $imitation->get('address', '*', NULL, $addressCon);

                $order_array = array(
                    'user_id'        => $user_id,
                    'address'        => $addressData[0]['id'],
                    'total'          => $total,
                    'payment_method' => 'Online',
                    'payment_id'     => $paymentId,
                    'created_at'     => date("Y-m-d H:i:s")
                );
                $orderResult = $imitation->insert('order_master', $order_array);

                if($orderResult) {
                    $order = "id DESC";
                    $limit = "1";
                    $ordersql = $imitation->get('order_master', '*', NULL, NULL, $order, $limit);
                    $orderId = $ordersql[0]['id'];
                }

                if(count($product) >= 1) {
                    foreach($product as $key => $val) {
                        $tmp = 0;
                        $tmp = $val['qty'] * $val['h_price'];
                        $order = array(
                            "order_id"   => $orderId,
                            "pro_id"     => $val['pro_id'],
                            "qty"        => $val['qty'],
                            "price"      => $tmp,
                            "pro_image"  => $val['pro_image'],
                            "created_at" => date("Y-m-d H:i:s")
                          );
                  
                          $orderDetailsResult = $imitation->insert("order_details", $order);
                    }
                }

                $name = $userData[0]['first_name'] . ' ' . $userData[0]['last_name'];
                $email = $userData[0]['email'];
                $subject = 'Order Confirm #0000' . $orderId; 
                $orderDate = date("Y-m-d H:i:s");
                $html = "Dear $name, <br><br>
                    Thank you for shopping with us! We're excited to confirm that your order has been successfully placed. Below, you'll find the details of your purchase:
                    <br><br>
                    Order Number: #0000$orderId
                    <br>
                    Order Date: $orderDate
                    <br>
                    Total Amount: $total
                    <br><br>
                    We are currently processing your order.
                    <br><br>
                    Thank you again for choosing to shop with us!
                    <br><br>
                    Best regards, <br>
                    <b>Heer's Imitation Jewellery House</b>
                    <br><br>
                    <img src='cid:logo' alt='Company Logo' style='height:80%; width:80%;'>";
                
                sendInvoic($orderId);
                
                if(smtp_mailer($email, $subject, $html)) {
                    $tmpCon = array('user_id' => $user_id);
                    $tmpOrderRemove = $imitation->delete('tmp_cart', $tmpCon);
                    $_SESSION['order-status'] = '1';
                    echo 'success';
                } else {
                    echo 'failed';
                } 
                exit;
            }
        }
    }
}

?>
<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:16 GMT -->
<head>
    <?php include_once('links.php'); ?>
</head>
<style>
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Adjust the alpha value for the desired level of blur */
  z-index: 9999; /* Ensures the loader is at the top layer */
}

.mesh-loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.mesh-loader .circle {
  width: 30px;
  height: 30px;
  position: absolute;
  background: goldenrod;
  border-radius: 50%;
  margin: -15px;
  animation: mesh 3s ease-in-out infinite -1.5s;
}

.mesh-loader > div .circle:last-child {
  animation-delay: 0s;
}

.mesh-loader > div:last-child {
  transform: rotate(90deg);
}

@keyframes mesh {
  0% {
    transform-origin: 50% -100%;
    transform: rotate(0);
  }
  50% {
    transform-origin: 50% -100%;
    transform: rotate(360deg);
  }
  50.1% {
    transform-origin: 50% 200%;
    transform: rotate(0deg);
  }
  100% {
    transform-origin: 50% 200%;
    transform: rotate(360deg);
  }
}
</style>
<body>
    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->

<!-- Body main wrapper start -->
<div class="body-wrapper">
    <div class="overlay" id="loader" style="display:none;">
        <div class="mesh-loader">
            <div class="set-one">
                <div class="circle"></div>
                <div class="circle"></div>
            </div>
            <div class="set-two">
                <div class="circle"></div>
                <div class="circle"></div>
            </div>
        </div>
    </div>
    <!-- HEADER AREA START (header-5) -->
    <?php include_once('header.php'); ?>
    <!-- HEADER AREA END -->
    
    <!-- Utilize Cart Menu Start -->
    <div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <span class="ltn__utilize-menu-title">Cart</span>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="mini-cart-product-area ltn__scrollbar">
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/1.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Red Hot Tomato</a></h6>
                        <span class="mini-cart-quantity">1 x $65.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/2.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Vegetables Juices</a></h6>
                        <span class="mini-cart-quantity">1 x $85.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/3.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Orange Sliced Mix</a></h6>
                        <span class="mini-cart-quantity">1 x $92.00</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img">
                        <a href="#"><img src="img/product/4.png" alt="Image"></a>
                        <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                    </div>
                    <div class="mini-cart-info">
                        <h6><a href="#">Orange Fresh Juice</a></h6>
                        <span class="mini-cart-quantity">1 x $68.00</span>
                    </div>
                </div>
            </div>
            <div class="mini-cart-footer">
                <div class="mini-cart-sub-total">
                    <h5>Subtotal: <span>$310.00</span></h5>
                </div>
                <div class="btn-wrapper">
                    <a href="cart.html" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                    <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Checkout</a>
                </div>
                <p>Free Shipping on All Orders Over $100!</p>
            </div>

        </div>
    </div>
    <!-- Utilize Cart Menu End -->

    <!-- Utilize Mobile Menu Start -->
    <?php include_once('mobile-header.php'); ?>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- WISHLIST AREA START -->
    <div class="ltn__checkout-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__checkout-inner">
                        <div class="ltn__checkout-single-content">
                            <h4 class="title-2">Billing Details</h4>
                            <div class="ltn__checkout-single-content-info">
                                <h3>Personal Details</h3>
                                <div class="row">
                                <?php 
                                    if(count($userData) >= 1) { ?>
                                    <div class="card" style="background-color: black; border: 1px solid gold;">
                                        <div class="card-body">
                                            <span><?php echo $userData[0]['first_name'] . ' ' . $userData[0]['last_name']; ?></span>
                                            <br>
                                            <span><?php echo $userData[0]['contact']; ?></span>
                                            <br>
                                            <span><?php echo $userData[0]['email']; ?></span>
                                            <a href="account.php?account=1" style="position: absolute; top: 10px; right: 10px;">Edit</a>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>
                                </div>
                            </div>
                            <div class="ltn__checkout-single-content-info mt-20" id="stopScrollHere">
                                <h3>Address Details</h3>
                                <div class="row">
                                <?php 
                                    $con = array('user_id' => $_SESSION['user_id']);
                                    $addressData = $imitation->get('address', '*', NULL, $con);

                                    if (count($addressData) >= 1) {
                                        foreach ($addressData as $key => $val) { 
                                            // Check if it's the first iteration, if so, add mt-4 class
                                            $mtClass = ($key == 1) ? 'mt-4' : '';

                                            ?>
                                            <div class="card <?php echo $mtClass; ?>" style="background-color: black; border: 1px solid gold;">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="address" name="address" <?php echo $val['default_status'] == '1' ? 'checked' : ''; ?> value="<?php echo $val['id']; ?>">
                                                        <label class="form-check-label" for="address">
                                                            <?php echo $val['address_line1'] . ', ' . $val['address_line2'] . ', ' . $val['city'] . ', ' . $val['state'] . ', ' . $val['country'] . ', ' . $val['zipcode']; ?>
                                                        </label>
                                                    </div>
                                                    <a href="edit-address.php?id=<?php echo base64_encode($val['id']); ?>" style="position: absolute; top: 0px; right: 10px;">Edit</a>
                                                    <span style="position: absolute; top: 2px; left: 4px;"><i class="icon-cancel"></i></span>
                                                </div>
                                            </div>
                                    <?php  
                                        }
                                    } else { ?>
                                        <div class="col-md-12">
                                            <span>No Address Found</span>
                                        </div>
                                    <?php 
                                        }
                                    ?>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-md-12">
                                        <a href="add-address.php" class="btn theme-btn-1 btn-effect-1">Add New Address</a>
                                    </div>
                                </div>
                                <span id="shipping-error" style="display:none;color:red;">Please add shipping addresss</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping-cart-total mt-50">
                        <h4 class="title-2">Cart Totals</h4>
                        <table class="table">
                            <tbody>
                                <?php
                                    $user_id = $_SESSION['user_id'] ? $_SESSION['user_id'] : null;

                                    if(isset($_SESSION['user_id']) && $_SESSION['email']) {
                                        $select ="tmp_cart.qty, product.id, product.name, product.primary_img, product.h_price";
                                        $joins = "LEFT JOIN tmp_cart ON tmp_cart.pro_id = product.id
                                                    WHERE tmp_cart.user_id='$user_id'
                                                    GROUP BY product.id
                                                    ORDER BY tmp_cart.created_at DESC ";
                                        $product = $imitation->get('product', $select, $joins);
                                
                                        $total = 0;
                                        foreach($product as $k => $v) { 
                                            $tmp = 0;
                                            $tmp = $v['qty'] * $v['h_price'];
                                        ?> 
                                        <tr>
                                            <td><?php echo $v['name'] . ' (₹' . $v['h_price']; ?> <strong>× <?php echo $v['qty'] . ')'; ?></strong></td>
                                            <td>₹ <?php echo $tmp; ?></td>
                                        </tr>
                                        <?php 
                                            $total += $tmp;
                                        }
                                    }
                                ?>
                                
                                <tr>
                                    <td><strong>Order Total</strong></td>
                                    <td><strong>₹ <?php echo $total >= 1 ? $total : 0; ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="cart.php" class="btn theme-btn-1 btn-effect-1 mt-10" type="submit">View Cart</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="ltn__checkout-payment-method mt-50">
                        <div class="ltn__payment-note mt-30 mb-30">
                            <p>Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our privacy policy.</p>
                        </div>
                        <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit" id="submit">Place order</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    $("#submit").click(function(event) {
        var addressCount = "<?php echo count($addressData); ?>"
        if(addressCount >= 1) {
            $('#shipping-error').css('display', 'none');
            var amt = "<?php echo $total; ?>";
            var options = {
                    "key": "rzp_test_eh4xkcqTW9H6ka",
                    "amount": amt * 100,
                    "currency": "INR",
                    "name": "Heers Imitation Jewellery House",
                    "description": "Test Transaction",
                    "color": "black",
                    "image": "logo.png",
                    "handler": function(response) {
                        if(response.razorpay_payment_id) {
                            getResponse(response.razorpay_payment_id);
                        } else {

                        }
                    },
                    "theme": {
                        "color": "black"
                    }
                };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        } else {
            $('#shipping-error').css('display', '');
            $('html, body').animate({
                scrollTop: $('#stopScrollHere').offset().top
            }, 100);
        }
    });

    function getResponse(paymentId) {
        $('#loader').css('display', '');
        $.ajax({
                type: 'post',
                url: 'checkout.php',
                data: {
                    type: 'payment',
                    payment_id: paymentId
                },
                success: function(result) {
                    console.log(result);
                    if(result == 'success') {
                        $('#loader').css('display', 'none');
                        setTimeout(function() {
                            swal({
                                title: 'Success',
                                text: 'Your Order Has Been Successful Confirm',
                                icon: 'success',
                            })
                        }, 1000);
                        window.setTimeout(function() {
                            window.location.href = 'success.php';
                        }, 5000);
                    } else {
                        swal({
                            title: 'Something Went Wrong !!!!',
                            text: 'OOPS, Something Went Wrong !!!!',
                            icon: 'error',
                        })
                    }
                }
        });
    }

</script>
</body>

<!-- Mirrored from tunatheme.com/tf/html/broccoli-preview/broccoli/checkout.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 02 Mar 2024 06:20:17 GMT -->
</html>

