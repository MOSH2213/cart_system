<?php
//in this first line the connection is included
include 'config.php';
// dn mehdi session ekk start karala thiyenawa 
session_start();
//$user_id kiyana variable ekata $_SESSION kiyana super global eken gnna adaala user ge user id eka store karanawa
$user_id = $_SESSION['user_id'];
//nikn hari ehma $user_id ekata monath thibbe nathm if condition eka haraha login page ekata yawanawa
if (!isset($user_id)) {
   header('location:login.php');
};
//thawada url eke logout kiyana eka set wela thibunanm $user_id variable eke value eka ain karala daanawa eeth ekkaama session eka delete karanwa and login.php page ekata redirect wenawa
if (isset($_GET['logout'])) {
   unset($user_id);
   session_destroy();
   header('location:login.php');
};
//ad to cart kiyaan dee unoth  pahala condiion execute wei
if (isset($_POST['add_to_cart'])) {
   //line 121 ta adaala input tag eke value eka an $product_name ekata store karanawa
   $product_name = $_POST['product_name'];
   //line 122 ta adaala input tag eke value eka an $product_price ekata store karanawa
   $product_price = $_POST['product_price'];
   //line 120 ta adaala input tag eke value eka an $product_image ekata store karanawa
   $product_image = $_POST['product_image'];
   //line 119 ta adaala input tag eke value eka an $product_quantuity ekata store karanawa
   $product_quantity = $_POST['product_quantity'];

   $product_id=$_POST['product_id'];

   //$select_cart kiyana variable ekata adala log wena user ge id eka ha adaala prouctname eka anuwa database eke query eka gahanawa(cart kiana table kata adaala weee)
   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
   //rows gaana check keermk wenaw methaana
   if (mysqli_num_rows($select_cart) > 0) {
      //ehma output ekk enawa nm query eka execute karaddi ee kiyanne cart table eke data tika add wena widiyata keewa nm cart eke adaala product eka include weeee
      $message[] = 'product already added to cart!';
      //ehma rows include nehe kiynn ecart eke hma product ekak adaala user ta nehe kiyan ekai ethkota else ru wee
   } else {
      //mee query ekedi cart table ekata adaala user ta anuwa product eka table ekata insert weee
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')") or die('query failed');
      //add unaama psse success message enawa
      $message[] = 'product added to cart!';
   }
};

//update car kiyana eka sidu unaanm conditio eka execute wei
if (isset($_POST['update_cart'])) {
   //$update_quantity ekata  175 line eke thiyena input tag eken ena cart table eke thiyena quantity value eka store kaanawa
   $update_quantity = $_POST['cart_quantity'];
   //update_totquantity kiynana variable ekedi wenne databas eke siraawatama thiyena produt stock eken , cart ekata add krahama psse ena quantiy eka pennana eka
   $update_totquantity = $_POST['product_quantity'] - ($_POST['cart_quantity']);
   $update_id = $_POST['cart_id'];
   $updateproduct_id = $_POST['product_id'];
   mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
   mysqli_query($conn, "UPDATE `products` SET quantity = '$update_totquantity' WHERE id= '$updateproduct_id'") or die('query failed');
   $message[] = 'cart quantity updated successfully!';
}

if (isset($_GET['remove'])) {
   $remove_id = $_GET['remove'];
   mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed');
   header('location:index.php');
}

if (isset($_GET['delete_all'])) {
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>
   <?php
   if (isset($message)) {
      foreach ($message as $message) {
         echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
      }
   }
   ?>

   <div class="container">

      <div class="user-profile">

         <?php
         $select_user = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_user) > 0) {
            $fetch_user = mysqli_fetch_assoc($select_user);
         };
         ?>

         <p> username : <span><?php echo $fetch_user['name']; ?></span> </p>
         <p> email : <span><?php echo $fetch_user['email']; ?></span> </p>
         <div class="flex">
            <a href="login.php" class="btn">login</a>
            <a href="register.php" class="option-btn">register</a>
            <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a>
         </div>

      </div>

      <div class="products">

         <h1 class="heading">latest products</h1>

         <div class="box-container">

            <?php
            $select_product = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
            if (mysqli_num_rows($select_product) > 0) {
               while ($fetch_product = mysqli_fetch_assoc($select_product)) {
            ?>
                  <form method="POST" class="box" action="">
                     <img src="<?php echo $fetch_product['image']; ?>" alt="">
                     <div class="name"><?php echo $fetch_product['name']; ?></div>
                     <div class="name"><?php echo $fetch_product['id']; ?></div>
                     <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
                     <input type="number" min="1" name="product_quantity" value="1">
                     <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                     <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                     <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                     <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                  </form>
            <?php
               };
            };
            ?>

         </div>
         <!-- <?php echo $update_quantity; ?> -->
      </div>

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
                              <input type="hidden" name="product_id" value="<?php echo $pid=$fetch_cart['product_id']; ?>">
                              <?php
                                 $product_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id='$pid'") or die('query failed');
                                 $fetch_product = mysqli_fetch_assoc($product_query);
                              ?>
                              <input type="hidden" name="product_quantity" value="<?php echo $fetch_product['quantity']; ?>">
                              <input type="number" min="0" max="<?php echo $fetch_product['quantity'] ?>" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                              <input type="submit" name="update_cart" value="update" class="option-btn">
                           </form>
                        </td>
                        <td>$<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</td>
                        <td><?php echo $protot = ($fetch_product['quantity']); ?>/-</td>
                        <td><a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remove item from cart?');">remove</a></td>
                     </tr>
               <?php
                     $grand_total += $sub_total;
                  }
               } else {
                  echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
               }
               ?>
               <tr class="table-bottom">
                  <td colspan="4">grand total :</td>
                  <td>$<?php echo $grand_total; ?>/-</td>
                  <td><a href="index.php?delete_all" onclick="return confirm('delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">delete all</a></td>
               </tr>
            </tbody>
         </table>

         <div class="cart-btn">
            <a href="#" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
         </div>

      </div>

   </div>

</body>

</html>