<?php
require 'index.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping cart</title>
    <style>
        .container {
            display: none;
        }
    </style>
    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/s1.css">

</head>

<body>
    <div class="container_1">
        <div class="shopping-cart">

            <h1 class="heading">shopping cart</h1>

            <table>
                <thead>
                    <th>image</th>
                    <th>name</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>total price</th>
                    <th>IN STOCK</th>
                    <th>action</th>
                </thead>
                <tbody>
                    <?php
                    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
                    $grand_total = 0;
                    $tot_prod=0;
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                    ?>

                            <tr>
                                <td><img src="<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td>$<?php echo $fetch_cart['price']; ?>/-</td>
                                <td>
                                    <form action="" method="POST">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type="hidden" name="update_product_id" value="<?php echo $pid = $fetch_cart['product_id']; ?>">
                                        <?php
                                        $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id='$pid'") or die('query failed');
                                        $fetch_product = mysqli_fetch_assoc($product_query);
                                        ?>
                                        <input type="hidden" name="product_quantity" value="<?php echo $fetch_product['quantity']; ?>">
                                        <input type="number" min="0" max="<?php echo $fetch_product['quantity'] ?>" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                        <button name="update_cart" class="option-btn">Update</button>
                                    </form>
                                </td>
                                <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</td>
                                <td><?php echo $protot = ($fetch_product['quantity']); ?>/-</td>
                                <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
                            </tr>
                    <?php
                            $grand_total += $sub_total;
                            //mee pahala thiyena array variable ekata add wena product id set eka store karanwa
                            $procount=array($fetch_product['id']);
                            //passe ee adala array eke indexes tika count karala gnnawa
                            $tot_prod += count($procount);
                        }
                    }
                     else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
                    }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">grand total :</td>
                        <td>$<?php echo $grand_total; ?>/-</td>
                        <td>peices <?php echo $tot_prod; ?>/-</td>
                        <td><a href="index.php?delete_all" onclick="return confirm('delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">delete all</a></td>
                    </tr>
                </tbody>
            </table>

            <div class="cart-btn">
                <a href="#" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
            </div>
            <div class="cart-btn">
                <a href="index.php" class="btn">Home</a>
            </div
        </div>
    </div>
</body>

</html>