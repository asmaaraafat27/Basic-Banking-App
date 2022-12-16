<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Transfers</title>
        <!-- CSS only -->
        <link rel="stylesheet" href="CSS/s.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <!-- end of links-->
    </head>
    <body>
            <!-- nav -->
            <section id="nav-bar">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="HomePage.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="Customers.php">Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="Transfers.php">Transfers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="Transfers-history.php">Transfers History</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            </section>
            <!-- end nav-->

            <!--select customers-->
            <section class="select-section">
                <h1 class="heading-title">Select a specific Customer</h1>
                <form method="GET" class="select-form">
                    <input type="number" id="f" name="select" placeholder="Select Customer ID" require>
                    <div class="button">
                        <button class="btn" type="submit" name="show">Show Customer</button>
                    </div>
                </form>
            </section>
            <section class="customers-hight">
                <table class="customers-ta">
                        <thead>
                            <tr>
                                <th>iD</th>
                                <th>first name</th>
                                <th>last name</th>
                                <th>e-mail</th>
                                <th>Current balance</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php
                                //start connection
                                $con = mysqli_connect("localhost" , "root" , "" , "banking_system");
                                //try connection
                                if($con -> connect_error){
                                    die("Connection Failed:" . $con-> connect_error);
                                }

                                if(isset($_GET['show']))
                                {
                                    $id = $_GET['select'];

                                    $sql = "SELECT * FROM customers WHERE ID=$id";
                                    $result = $con->query($sql);

                                    if($result->num_rows > 0) {
                                        while($row = $result -> fetch_assoc())
                                        {
                                            echo "<tr><td>".$row["ID"]."</td><td>".$row["First Name"]."</td><td>".$row["Last Name"]."</td><td>".$row["E-mail"]."</td><td>".$row["Current Balance"];
                                        }
                                        echo "</table>";
                                    }
                                    else if($result -> num_rows <= 0)
                                    {
                                        echo "<h4>  No result founded</h4>";
                                    }
                                }
                            ?>
                    </tbody>
                </table>
            </section>
            <!--end-->
            <!-- transfer section-->
            <section class="transfer">
                <h1 class="heading-title">Transfer Operation</h1>
                <div class="container">
                    <form method="POST" class="transfer-form">
                        <?php
                            //start connection
                            $con = mysqli_connect("localhost" , "root" , "" , "banking_system");
                            //try connection
                            if($con -> connect_error){
                                die("Connection Failed:" . $con -> connect_error);
                            }
                            if(isset($_POST['submit']))
                            {
                                $sender = $_POST['sender'];
                                $receiver = $_POST['receiver'];
                                $amount = $_POST['amount'];
        
                                if($sender <= 0 || $receiver <= 0 || $amount <= 0)
                                {
                                    echo '<script>alert("Error Massage: Values must be more than zero!")</script>';
                                }
                                else
                                {
                                    //for sender
                                    $sqlGetSender = "SELECT * from customers where ID=$sender";
                                    $sender_query = mysqli_query($con, $sqlGetSender);
                                    $sqlSender = mysqli_fetch_array($sender_query);
        
                                    //for reciver
                                    $sqlGetReceiver = "SELECT * from customers where ID=$receiver";
                                    $receive_query = mysqli_query($con, $sqlGetReceiver);
                                    $sqlReceiver = mysqli_fetch_array($receive_query);
        
                                    if($amount < $sqlSender['Current Balance'])
                                    {
                                        $new_balance_sender = $sqlSender['Current Balance'] - $amount;
                                        $sql = "UPDATE customers set Current Balance=$new_balance_sender where ID=$sender";
                                        mysqli_query($con, $sql);
        
                                        $new_balance_receiver = $sqlReceiver['Current Balance'] + $amount;
                                        $sql = "UPDATE customers set Current Balance=$new_balance_receiver where ID=$receiver";
                                        mysqli_query($con, $sql);
        
        
                                        $sender_id = $sqlSender['ID'];
                                        $receiver_id = $sqlReceiver['ID'];
        
                                        $Insertsql = "INSERT INTO `transfers` (`transfer_num`, `Sender ID`, `Receiver ID`, `Amount`, `date`) VALUES ('NULL','$sender_id ','$receiver_id','$amount', current_timestamp())";
        
                                        $insert = mysqli_query($con, $Insertsql);
        
                                    }
                                    else if($amount >= $sqlSender['Current Balance'])
                                    {
                                        echo '<script>alert("Opps, the amount of money is higher than you have")</script>';
                                    }
                                }
                            }
                        ?> 
                        <label class="from">Transfer From</label>
                        <input type="number" id="form" name="sender" placeholder="Enter customer Id">
                        <label class="to">Transfer To</label>
                        <input type="number" id="to" name="receiver" placeholder="Enter customer Id">
                        <label class="amount">Amount</label>
                        <input type="number" id="amount" name="amount" placeholder="Enter the amount">
                        <div class="btnholder">
                            <button class="btn" type="submit" name="submit">Transfer</button>
                        </div>
                    </form>
                </div>
             </section>
        <!-- Transfer Section End -->
    </body>
</html>