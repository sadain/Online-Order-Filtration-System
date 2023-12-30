<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking System</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet"/>
</head>
<body>
<?php 
    $con = mysqli_connect("localhost","root","root","OrderDatabase");
?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>ORDER TRACKING SYSTEM</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Click to Filter</label> <br>
                                      <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="container">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h3 align="center">Sales Summary</h3>
                                        <table class="table table-borderd">
                                            <thead>
                                                <th>Description</th>
                                                <th>order</th>
                                                <th>Income</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                                    {
                                                        $from_date = $_GET['from_date'];
                                                        $to_date = $_GET['to_date'];

                                                        $query = "SELECT count(o.orders_id) as total_orders, ROUND(SUM(op.final_price * op.products_quantity), 0) as total_sales from orders o JOIN orders_products op ON o.orders_id = op.orders_products_id WHERE date_purchased BETWEEN '$from_date' AND '$to_date';"; 
                                                        
                                                        $result = mysqli_query($con, $query);
                                                        
                                                        $row =  mysqli_fetch_assoc($result);                                        
                                                                                                
                                                        ?>
                                                        <tr>
                                                            <td>Sale's</td>
                                                            <td><?= $row['total_orders']; ?></td>
                                                            <td><?= $row['total_sales']; ?></td>
                                                            
                                                        </tr>
                                                        

                                                        <?php
                                                    
                                                    }

                                                    $query1 = "SELECT count(o.orders_id) as todays_orders, ROUND(SUM(op.final_price * op.products_quantity), 0) as todays_sales from orders o JOIN orders_products op ON o.orders_id = op.orders_products_id WHERE date_purchased = curdate();"; 
                                                    $result1 = mysqli_query($con, $query1);
                                                    $rows =  mysqli_fetch_assoc($result1);
                                                    ?>
                                                    <tr>
                                                        <td>Today's Sales</td>
                                                        <td><?= $rows['todays_orders']; ?></td>
                                                        <td><?= $rows['todays_sales']; ?></td>
                                                    </tr>
                                                    <?php
                                                    
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- ------------------------------------------ -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h3 align="center">Month Total</h3>
                                        <table class="table table-borderd">
                                            <thead>
                                                <th>Month</th>
                                                <th>total Month Orders</th>
                                                <th>Income</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    
                                                    if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                                    {
                                                        $from_date = $_GET['from_date'];
                                                        $to_date = $_GET['to_date'];

                                                        
                                                        $query = "SELECT DATE_FORMAT(o.date_purchased, '%M %Y') AS month_year, COUNT(o.orders_id) AS total_orders, ROUND(SUM(op.final_price * op.products_quantity), 0) AS total_sales
                                                        FROM orders o
                                                        JOIN orders_products op ON o.orders_id = op.orders_products_id
                                                        WHERE o.date_purchased BETWEEN '$from_date' AND '$to_date'
                                                        GROUP BY DATE_FORMAT(o.date_purchased, '%M %Y')
                                                        ORDER BY o.date_purchased";

                                                        $result = mysqli_query($con, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {                                      
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $row['month_year']; ?></td>
                                                                <td><?php echo $row['total_orders']; ?></td>
                                                                <td><?php echo $row['total_sales']; ?></td>
                                                                
                                                            </tr>
                                                            <?php  
                                                        }
                                                        // mysqli_close($conn);
                                                    }
                                                    
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- --------------------------------------------------------------------- -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h3 align="center">Customer Information</h3>
                                        <table class="table table-borderd">
                                            <thead>
                                                <th>Description</th>
                                                <th>Counts</th>                                               
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $sql = "SELECT COUNT(customers_info_id) FROM customers_info";
                                                    $total_result = mysqli_query($con, $sql);

                                                    $sql = "SELECT COUNT(customers_info_id) FROM customers_info WHERE customers_info_date_account_created = curdate()";
                                                    $today_result = mysqli_query($con, $sql);
                                                    
                                                    $sql = "SELECT COUNT(customers_info_id) FROM customers_info WHERE customers_info_date_account_created >= DATE_SUB(curdate(), INTERVAL 1 MONTH)";
                                                    $month_result = mysqli_query($con, $sql);

                                                    $row =  mysqli_fetch_assoc($total_result);
                                                    $row1 =  mysqli_fetch_assoc($today_result);
                                                    $row2 =  mysqli_fetch_assoc($month_result);

                                                    ?>
                                                    <tr>
                                                        <th>Total Customers</th>
                                                        <td><?php echo $row['COUNT(customers_info_id)']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Today's Customers</th>
                                                        <td><?php echo $row1['COUNT(customers_info_id)']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>This Month's Customers</th>
                                                        <td><?php echo $row2['COUNT(customers_info_id)']; ?></td>
                                                    </tr>
                                                    <?php
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
            
                                <!-- ------------------------------------sad--------------------------------- -->
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h3 align="center">Order Status</h3>
                                        <table class="table table-borderd">
                                            <thead>
                                                <th>Status Name</th>
                                                <th>No. Counts</th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                            <?php 
                                                $query = "SELECT orders_status_name, COUNT(*) as count FROM orders_status INNER JOIN orders ON orders.orders_status = orders_status.orders_status_id WHERE orders_status_name IN ('Pending', 'Processing', 'Shipped. W/Payment Instructions', '(Payment Received) Thank You', 'Transferred & CCC', 'Paid (Discounted)', 'Paid (Invoice Issued)', 'Cancelled & FULL Refund', 'Awaiting Payment', 'In Correspondence', 'Priority Order', 'REFUND / or Request Xtra Payment', 'Processing (Priority)', 'Approved (Priority Order)', 'Part to Follow', 'Paypal Payment', 'Nochex Payment', 'Cheque Payment', 'Bank Trsf Payment', 'Cash Payment', 'TO COLLECT', 'Payment Sense Verified', 'Payment Failed', 'Payment Successful' ) GROUP BY orders_status_name";
                                                $result = $con->query($query);
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $row["orders_status_name"];?></td>
                                                            <td><?php echo $row["count"];?></td>
                                                        </tr>
                                                        
                                                    
                                                        <?php
                                                    } 
                                                }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h3 align="center">Grand Total</h3>
                                        <table class="table table-borderd">
                                            <thead>
                                                <th>Description</th>
                                                <th>Total Orders</th>
                                                <th>Total Income</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $query = "SELECT count(o.orders_id) as total_orders, ROUND(SUM(op.final_price * op.products_quantity), 0) as total_sales from orders o JOIN orders_products op ON o.orders_id = op.orders_products_id";
                                                    $result = mysqli_query($con, $query);
                                                    $row =  mysqli_fetch_assoc($result);                                        
                                                ?>
                                                    <tr>
                                                        <th>Grand Total</th>
                                                        <td><?= $row['total_orders']; ?></td>
                                                        <td><?= $row['total_sales']; ?></td>
                                                        
                                                    </tr>
                                                <?php
                                                ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- kjdbcjksbshhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh -->


                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <h3 align="center">Order by country</h3>
                                                    <table class="table table-borderd">
                                                        <thead>
                                                            <th>Country</th>
                                                            <th>total Orders</th>
                                                            <th>order Value</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                                                {
                                                                    $from_date = $_GET['from_date'];
                                                                    $to_date = $_GET['to_date'];
                        
                                                                    
                                                                    $query = "SELECT o.customers_country, COUNT(o.orders_id) AS total_orders, ROUND(SUM(op.final_price * op.products_quantity), 0) AS total_sales
                                                                    FROM orders o
                                                                    JOIN orders_products op ON o.orders_id = op.orders_products_id
                                                                    WHERE o.date_purchased BETWEEN '$from_date' AND '$to_date'
                                                                    GROUP BY o.customers_country";
                                                                    
                                                                    $result = mysqli_query($con, $query);
                                                                    while ($row = mysqli_fetch_assoc($result)) {                                      
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $row['customers_country']; ?></td>
                                                                            <td><?php echo $row['total_orders']; ?></td>
                                                                            <td><?php echo $row['total_sales']; ?></td>
                                                                            
                                                                        </tr>
                                                                        <?php  
                                                                    }
                                                                    // mysqli_close($conn);
                                                                }
                                                                
                                                                ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="card mt-4">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <h3 align="center">Order by Zone</h3>
                                                    <table class="table table-borderd">
                                                        <thead>
                                                            <th>Zone</th>
                                                            <th>total Orders</th>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                                            {
                                                                $from_date = $_GET['from_date'];
                                                                $to_date = $_GET['to_date'];

                                                                
                                                                $query = "SELECT o.customers_city, COUNT(o.orders_id) AS total_orders, SUM(op.final_price * op.products_quantity) AS total_sales
                                                                FROM orders o
                                                                JOIN orders_products op ON o.orders_id = op.orders_products_id
                                                                WHERE o.date_purchased BETWEEN '$from_date' AND '$to_date'
                                                                GROUP BY o.customers_city";

                                                                $result = mysqli_query($con, $query);
                                                                while ($row = mysqli_fetch_assoc($result)) {                                      
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $row['customers_city']; ?></td>
                                                                            <td><?php echo $row['total_orders']; ?></td>
                                                                            
                                                                        </tr>
                                                                        <?php  
                                                                }
                                                                // mysqli_close($conn);
                                                            }
                                                            
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                


                 
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.js"></script>
</body>
</html>