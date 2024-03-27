<?php

    include_once("config.php");
    $imitation = new imitation();
    $msg = "";

    $orderid = base64_decode($id);
    $select = "order_master.id as orderID, order_master.created_at as orderDate, 
               users.first_name, users.last_name, users.email, users.contact, 
               address_line1, address_line2, state, city, country, zipcode";
    $joins = "LEFT JOIN 
                users ON users.id = order_master.user_id
            LEFT JOIN
                address ON address.user_id = users.id
            WHERE 
                order_master.id = $orderid AND address.default_status = 1";
    $userDet = $imitation->get('order_master', $select, $joins);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Invoice</title>
</head>
<body>
    <div style="text-align:center;">
        <img src="logo.png" alt="" style="height:120px;width:250px;">
    </div>
    <h1 style="font-family: Arial, sans-serif; text-align:center;">Heers Imitation Jewellery House</h1>
    <p style="font-family: Arial, sans-serif; text-align:center; margin-top:-20px;">Ahmedabad, Gujarat, India. </p>
    <h3 style="font-family: Arial, sans-serif; text-align:center;">Invoice</h3>
    <table style="width: 100%;">
    <tr>
        <td style="width: 45%; padding: 10px;"><b>Invoice to : </b>
            <p><b><?php echo $userDet[0]['first_name'] . ' ' . $userDet[0]['last_name']; ?></b></p>
            <p><?php echo $userDet[0]['address_line1'] . ", " . $userDet[0]['address_line2'] . "," . "<br>" . $userDet[0]['state'] . ", " . $userDet[0]['city'] . ", " . $userDet[0]['zipcode'];; ?></p>
            <p><?php echo $userDet[0]['contact']; ?></p>
        </td>
        <td style="width: 45%; padding: 10px; text-align:right"><p><b>Invoice :</b> #0000<?php echo $userDet[0]['orderID']; ?></p>
          <p><b>Date :</b> <?php echo date("d/m/Y", strtotime($userDet[0]['orderDate'])); ?></p>
        </td>
    </tr>
</table>
  
  <table style="width: 100%; border-collapse: collapse; border:1px solid black">
    <thead style="background-color:black; color:white;">
      <tr style="border-bottom: 1px solid black;">
        <th style="text-align: left; padding: 8px;">SL</th>
        <th style="text-align: left; padding: 8px;">Item Description</th>
        <th style="text-align: left; padding: 8px;">Item Image</th>
        <th style="text-align: right; padding: 8px;">Qty</th>
        <th style="text-align: right; padding: 8px;">Total</th>
      </tr>
    </thead>
    <tbody>
    <?php
        $select ="order_master.id as order_id,
            order_details.qty as order_qty,
            order_details.price as price,
            order_master.total as total,
            order_master.created_at as order_date,
            product.id as product_id,
            product.name as product_name,
            order_details.pro_image as product_image";
        $joins = "LEFT JOIN 
                order_details ON order_master.id = order_details.order_id
            LEFT JOIN 
                product ON order_details.pro_id = product.id
            WHERE 
                order_details.order_id = $orderid
            GROUP BY product.id";
        $product = $imitation->get('order_master', $select, $joins);
        $serial_number = 1; 

        foreach($product as $key => $val) {
    ?>
      <tr>
        <td style="text-align: left; padding: 8px;"><?php echo $serial_number++; ?></td>
        <td style="text-align: left; padding: 8px;"><?php echo $val['product_name']; ?></td>
        <td style="text-align: left; padding: 8px;"><img src="img/product/<?php echo $val['product_image']?>" alt="" style="height:50px;width:50px;"></td>
        <td style="text-align: right; padding: 8px;"><?php echo $val['order_qty']; ?></td>
        <td style="text-align: right; padding: 8px;"><?php echo $val['price']; ?></td>
      </tr>
    <?php } ?>
      <tr></tr>
      <tr style="border:1px solid black;">
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:right;"><b>Sub Total</b></td>
        <td style="text-align: right; padding: 8px;"><?php echo $product[0]['total']; ?></td>
      </tr>
      <tr style="border:1px solid black;">
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:right;"><b>Total</b></td>
        <td style="text-align: right; padding: 8px;"><?php echo $product[0]['total']; ?></td>
      </tr>
    </tbody>
  </table>
</body>
</html>