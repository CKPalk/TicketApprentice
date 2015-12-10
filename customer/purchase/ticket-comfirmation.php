
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );


$first_name = $_POST['customer_first'];
$last_name = $_POST['customer_last'];
$email = $_POST['email'];
$address_line_1    = $_POST["address_line_1"];
$address_line_2    = $_POST["address_line_2"];
$city              = $_POST["city"];
$state             = $_POST["state"];
$zipcode           = $_POST["zipcode"];
$country           = $_POST["country"];

$event_id = $_GET['event_id'];
$row_id             = $_POST['row_id'];
$ticket_count = $_POST['ticket_count'];

$stmt = mysqli_prepare( $connection, "SELECT row_ticket_price FROM Venue_Rows WHERE event_id=? AND row_id=?;");
mysqli_stmt_bind_param( $stmt, 'ii', $event_id, $row_id );
mysqli_stmt_execute( $stmt );
mysqli_stmt_bind_result( $stmt, $row_price );
mysqli_stmt_fetch( $stmt );
mysqli_stmt_close( $stmt );


$preparedAddressInsert = "
    INSERT INTO Addresses (
        address_line_1,
        address_line_2,
        city,
        state,
        zipcode,
        country
    ) VALUES ( ?, ?, ?, ?, ?, ? );
";

$preparedAddressQuery = "
    SELECT address_id FROM Addresses WHERE 
        address_line_1=? AND 
        address_line_2=? AND
        city=? AND
        state=? AND
        zipcode=? AND
        country=?
    ;
";

// Insert into Customer table here
$preparedCustomerInsert = "
    INSERT INTO Customers (
        first_name,
        last_name,
        email,
        address_id
    ) VALUES ( ?, ?, ?, ? );
";


$preparedCustomerQuery = "
    SELECT customer_id FROM Customers WHERE
        first_name=? AND last_name=? AND email=? AND address_id=?
    ;
";

?>

<html>
<head>
    <title>TA: Purchase Tickets</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css?v=<?=time();?>">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css?v=<?=time();?>">
</head>
<body>
<!-- HEADER BAR 8 lines-->
    <div id="header-bar" class="manager-background">
        <div id="header-container">
            <a id="customer-home-header" href="https://ix.cs.uoregon.edu/~cpalk/TicketApprentice/customer/">Customer Home</a>
            <a id="manager-home-header" href="https://ix.cs.uoregon.edu/~cpalk/TicketApprentice/manager/">Manager Home</a>
        </div>
    </div>
<!-- END HEADER -->
<div id="body_container">
<div id="middle_container">
    <div class="module">
        <div class="module-title">Tickets</div>
        <div class="module-body centered">
            
<?php
            $customer_id = -1;

            if ( $stmt = mysqli_prepare( $connection, $preparedAddressInsert ) ) {
                mysqli_stmt_bind_param( $stmt, 'ssssis', $address_line_1, $address_line_2, $city, $state, $zipcode, $country); 
                if ( mysqli_stmt_execute( $stmt ) ) {
                echo "<div class='stmt-success'>1. Address Prepared Statement Passed</div>";
                    mysqli_stmt_close( $stmt );
                    if ( $stmt = mysqli_prepare( $connection, $preparedAddressQuery ) ) {
                    echo "<div class='stmt-success'>2. Address Inserted</div>";
                        mysqli_stmt_bind_param( $stmt, 'ssssis', $address_line_1, $address_line_2, $city, $state, $zipcode, $country);
                        if ( mysqli_stmt_execute( $stmt ) ) {
                            echo "<div class='stmt-success'>3. Address Entry Found</div>";
                            mysqli_stmt_bind_result( $stmt, $address_id );
                            mysqli_stmt_fetch( $stmt );
                            mysqli_stmt_close( $stmt );
                            if( $stmt = mysqli_prepare( $connection, $preparedCustomerInsert ) ) { // here
                                echo "<div class='stmt-success'>4. Customer Prepared Statement Passed</div>";
                                mysqli_stmt_bind_param( $stmt, 'sssi', $first_name, $last_name, $email, $address_id);
                                if ( mysqli_stmt_execute( $stmt ) ) {
                                    echo "<div class='stmt-success'>5. Customer Inserted</div>";
                                    mysqli_stmt_close( $stmt );
                                    if ( $stmt = mysqli_prepare( $connection, $preparedCustomerQuery ) ) {
                                        mysqli_stmt_bind_param( $stmt, 'sssi', $first_name, $last_name, $email, $address_id );
                                        if ( mysqli_stmt_execute( $stmt ) ) {
                                            mysqli_stmt_bind_result( $stmt, $customer_id );
                                            mysqli_stmt_fetch( $stmt );
                                        } else { echo "<div class='stmt-failed'>Customer Query Execution Failed</div>"; }
                                    }
                                }
                                else { echo "<div class='stmt_failed'>Customer Insert Execution Failed</div>"; }
                            }
                            else { echo "<div class='stmt-failed'>Customer Prepared Statement Passed</div>"; }
                        }
                        else { echo "<div class='stmt-failed'>Address Query Execution Failed</div>"; }
                    }
                }
                else { echo "<div class='stmt-failed'>Address Insert Execution Failed</div>"; }
            }
            else { echo "<div class='stmt-failed'>Address Insert Prepared Statement Failed</div>"; }


            if ( $customer_id != -1 ) {

                $total_price = $ticket_count * $row_price;
                mysqli_stmt_close( $stmt );

                // Create new customer order
                if ( $stmt = mysqli_prepare( $connection, "INSERT INTO Customer_Orders ( order_date, money_paid, customer_id, event_id) VALUES ( ?, ?, ?, ? );" ) ) {
                    mysqli_stmt_bind_param( $stmt, 'sdii', date("Y-m-d"), $total_price, $customer_id, $event_id );
                    if ( mysqli_stmt_execute( $stmt ) ) {
                        echo "<div class='stmt-success'>6. Customer Order Created</div>";
                    } else { echo "<div class='stmt-failed'>Customer Order Execution Failed</div>"; }
                    mysqli_stmt_close( $stmt );
                } else { echo "<div class='stmt-failed'>Customer Order Prepared Statement Failed</div>"; }

            }

            if ( $stmt = mysqli_prepare( $connection, "SELECT order_id FROM Customer_Orders WHERE order_date=? AND customer_id=? AND event_id=?;" ) ) {
                mysqli_stmt_bind_param( $stmt, 'sii', date("Y-m-d"), $customer_id, $event_id );
                mysqli_stmt_execute( $stmt );
                mysqli_stmt_bind_result( $stmt, $order_id );
                mysqli_stmt_fetch( $stmt );
                mysqli_stmt_close( $stmt );
            }

            // find last seat
            if ( $stmt = mysqli_prepare( $connection, "SELECT seat FROM Seat_Reservations WHERE event_id=? AND row_id=? ORDER BY seat DESC;"  )) {
                mysqli_stmt_bind_param( $stmt, 'ii', $event_id, $row_id );
                mysqli_stmt_execute( $stmt );
                mysqli_stmt_bind_result( $stmt, $seat );
                if ( !mysqli_stmt_fetch( $stmt ) ) {
                    $seat = 0;
                }
            }

            if ( $stmt = mysqli_prepare( $connection, "INSERT INTO Seat_Reservations( event_id, row_id, order_id, seat ) VALUES ( ?, ?, ?, ? );"  )) {
                $bookedTickets = true;
                for ( $seat_index = 0; $seat_index < $ticket_count; $seat_index++ ) {
                    $seatToTake = ($seat + 1) + $seat_index;
                    mysqli_stmt_bind_param( $stmt, 'iiii', $event_id, $row_id, $order_id, $seatToTake );
                    if ( !mysqli_stmt_execute( $stmt ) ) { $bookedTickets = false; }
                }
                if ( $bookedTickets ) { echo "<div class='stmt-success'>7. Customer Seats Booked</div>"; }
                else { echo "<div class='stmt-failed'>Error Booking Seats</div>"; }
                mysqli_stmt_close ($stmt );
            }


?>



            <br>

            <a id="details-link" href="../view/tickets.php?customer_id=<?php echo $customer_id ?>">View Your Tickets</a>
        </div>
    </div>
</body>
</html>
















