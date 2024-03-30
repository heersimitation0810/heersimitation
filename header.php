<header class="ltn__header-area ltn__header-5 ltn__header-transparent-- gradient-color-4---">
        <!-- ltn__header-middle-area start -->
        <div style="background-color:black;" class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white sticky-active-into-mobile ltn__logo-right-menu-option plr--9---">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap">
                            <div class="site-logo">
                                <a href="index.php"><img src="logo.png" alt="Logo" height="70" width="170"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col header-menu-column menu-color-white---">
                        <div class="header-menu d-none d-xl-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li><a href="index.php">Home</a></li>
                                        <li>
                                            <a href="#">Category</a>
                                            <ul>
                                                <li><a href="shop.php?catid=1">Earring</a></li>
                                                <li><a href="shop.php?catid=2">Bracelets</a></li>
                                                <li><a href="shop.php?catid=3">Ring</a></li>
                                                <li><a href="shop.php?catid=4">Necklace</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="contact.php">Contact</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="ltn__header-options ltn__header-options-2 mb-sm-20">
                        <!-- user-menu -->
                        <div class="ltn__drop-menu user-menu">
                            <ul>
                                <li>
                                    <a href="#"><i class="icon-user" style="color:goldenrod;"></i></a>
                                    <ul>
                                        <?php 
                                            if(!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) { ?>
                                                <li><a href="login.php">Sign in</a></li>
                                                <li><a href="register.php">Register</a></li>
                                        <?php } else { ?>
                                                <li><a href="order.php">Orders</a></li>
                                                <li><a href="account.php">My Account</a></li>
                                                <li><a href="wishlist.php">Wishlist</a></li>
                                                <li><a href="address.php">Address</a></li>
                                                <li><a href="logout.php">Logout</a></li>
                                        <?php 
                                            }
                                        ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- mini-cart -->
                        <?php 
                            if(isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
                                $con = array('user_id' => $_SESSION['user_id']);
                                $totalProduct = $imitation->get('tmp_cart', '*', NULL, $con);
                            }
                        
                        ?>
                        <div class="mini-cart-icon">
                            <a href="cart.php">
                                <i class="icon-shopping-cart" style="color:goldenrod;"></i>
                                <sup style="color:goldenrod;" class="totalPro"><?php 
                                    if(isset($_SESSION['user_id']) && isset($_SESSION['email'])) {
                                        echo count($totalProduct) >= 1 ? count($totalProduct) : ''; 
                                    }
                                ?>
                                </sup>
                            </a>
                        </div>
                        <!-- mini-cart -->
                        <!-- Mobile Menu Button -->
                        <div class="mobile-menu-toggle d-xl-none">
                            <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                <svg viewBox="0 0 800 600">
                                    <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                    <path d="M300,320 L540,320" id="middle"></path>
                                    <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ltn__header-middle-area end -->
    </header>