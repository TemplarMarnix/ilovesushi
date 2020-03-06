<!DOCTYPE html>
<html>
<head>
    <?php
    session_start();
    $_SESSION["category"] = "";
    $category = $_SESSION["category"];
    require('common/connect.php');
    require('common/links.php');
    require('common/shoppingcart.php');
    require('common/category.php');

    $shippingprice = 5;
    ?>
    <title>iLoveSushi Zeist</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row header">
            <?php require('header.php'); ?>
        </div>
        <div class="row content-top">
            <div class="col-lg-2 col-md-2">
                <!-- leftside -->
            </div>
            <div class="col-lg-1 col-md-1">categorieën</div>
            <?php
            $string = $category;
            $categoryName = preg_replace("/'/", "", $string);
            ?>
            <div class="col-lg-5 col-md-5"><?php echo $categoryName; ?></div>
            <div class="col-lg-2 col-md-2">winkelwagen</div>
            <div class="col-lg-2 col-md-2">
                <!-- rightside -->
            </div>
        </div>
        <div class="row content-middle">
            <div class="col-lg-2 col-md-2"></div>
            <div class="col-lg-1 col-md-1">
                <?php
                $categories = ['vipboxen', 'appetizers', 'nigiri', 'hosomaki', 'softshellrolls', 'temakihandroll', 'uramaki', 'outsidecrispyrolls', 'friedcrispyrolls', 'pokebowl', 'sashimi', 'dranken'];

                foreach ($categories as $ar) {
                    if ($ar == $categoryName) {
                        $$ar = 'active-category';
                    }
                }
                ?>
                <form method="post" action="">
                    <ul>
                        <li><button class="<?php echo $vipboxen; ?>" type="submit" name="vipboxen">Vip Boxen</button></li>
                        <li><button class="<?php echo $appetizers; ?>" type="submit" name="appetizers">Appetizers</button></li>
                        <li><button class="<?php echo $nigiri; ?>" type="submit" name="nigiri">Nigiri</button></li>
                        <li><button class="<?php echo $hosomaki; ?>" type="submit" name="hosomaki">Hosomaki</button></li>
                        <li><button class="<?php echo $softshellrolls; ?>" type="submit" name="softshell">Soft Shell rolls</button></li>
                        <li><button class="<?php echo $temakihandroll; ?>" type="submit" name="temaki">Temaki handroll</button></li>
                        <li><button class="<?php echo $uramaki; ?>" type="submit" name="uramaki">Uramaki</button></li>
                        <li><button class="<?php echo $outsidecrispyrolls; ?>" type="submit" name="outsidecrispyrolls">Outside Crispy rolls</button></li>
                        <li><button class="<?php echo $friedcrispyrolls; ?>" type="submit" name="friedcrispyrolls">Fried Crispy rolls</button></li>
                        <li><button class="<?php echo $pokebowl; ?>" type="submit" name="pokebowls">Poke Bowls</button></li>
                        <li><button class="<?php echo $sashimi; ?>" type="submit" name="sashimi">Sashimi</button></li>
                        <li><button class="<?php echo $dranken; ?>" type="submit" name="dranken">Dranken</button></li>
                    </ul>
                </form>
            </div>
            <div class="col-lg-5 col-md-5 d-flex flex-wrap">
                <?php
                $sql = "SELECT * FROM product WHERE category=" . $category;
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <div class="col-lg-4 col-md-4">
                            <div class="product">
                                <img src="<?php echo $row['image']; ?>" alt="foto"><br>
                                <div class="information">
                                    <span><?php echo $row['name'] ?></span>
                                    <span class="price">€ <?php echo $row['price']; ?></span>
                                    <hr>
                                    <form method="post" action="index.php?action=add&id=<?php echo $row['id']; ?>">
                                        <div class="amount d-flex ">
                                            <button class="minus">-</button>
                                            <input type="number" name="quantity" min="1" value="1">
                                            <button class="plus">+</button>
                                            <input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>">
                                            <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
                                            <button class="add-to-cart" type="submit" name="add_to_cart"><i class="fas fa-cart-plus"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    echo "Sorry this product is sold out.";
                }
                ?>
            </div>
            <div class="colg-lg-2 col-md-2 cart">
                <table>
                    <tr>
                        <td>Naam</td>
                        <td>Aantal</td>
                        <td>Prijs (x1)</td>
                        <td>Totaal</td>
                        <td></td>
                    </tr>
                    <?php
                    if (!empty($_SESSION["shopping_cart"])) {
                        $total = 0;
                        foreach ($_SESSION["shopping_cart"] as $keys => $values) {
                            ?>
                            <tr>
                                <td><?php echo $values["item_name"]; ?></td>
                                <td class="quantity">x<?php echo $values["item_quantity"]; ?></td>
                                <td>€ <?php echo $values["item_price"]; ?></td>
                                <td>€ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2); ?></td>
                                <td><a href="index.php?action=delete&id=<?php echo $values["item_id"]; ?>"><i class="fas fa-times"></i></a></td>
                            </tr>
                            <?php
                                $total = $total + ($values["item_quantity"] * $values["item_price"]) + 0.1 + $shippingprice;
                            }
                            ?>
                        <tr class="extra">
                            <td>Tasje</td>
                            <td></td>
                            <td></td>
                            <td>+ € 0,10</td>
                            <td></td>
                        </tr>
                        <tr class="alert alert-danger">
                            <td colspan="3">Bezorgkosten verschilt per postcode!</td>
                            <td>+ € 5,00</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th colspan="3">Totaal</th>
                            <th>€ <?php echo number_format($total, 2); ?></th>
                            <td></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <button class="proceed-checkout">Afrekenen</button>
            </div>
        </div>
        <?php
        require('footer.php');
        $conn->close(); ?>
    </div>
    <!-- LINKS START -->
    <?php require('common/scripts.php'); ?>
    <!-- LINKS END -->
</body>
</html>