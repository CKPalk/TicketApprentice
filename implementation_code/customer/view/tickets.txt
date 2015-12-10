
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

if ( isset( $_GET['customer_id'] ) )
    $customer_id = $_GET['customer_id'];
else
    print 'Nothing found for customer id';


$preparedReservationQuery = "
    SELECT C.first_name, C.last_name, CO.order_id, CO.order_date, CO.event_id, E.event_name, E.event_start_date
    FROM Customer_Orders CO JOIN Events E USING( event_id ) LEFT OUTER JOIN Customers C USING( customer_id )
    WHERE CO.customer_id=?;
";

$preparedSeatQuery = "
    SELECT SR.row_id, SR.seat
    FROM Seat_Reservations SR LEFT OUTER JOIN Customer_Orders CO USING ( order_id )
    WHERE SR.order_id=?;
";


if ( $stmt = mysqli_prepare( $connection, $preparedReservationQuery ) ) {
    mysqli_stmt_bind_param( $stmt, 'i', $customer_id );
    if ( mysqli_stmt_execute( $stmt ) ) {
        mysqli_stmt_bind_result( $stmt, $first_name, $last_name, $order_id, $order_date, $event_id, $event_name, $event_start_date );
        mysqli_stmt_fetch( $stmt );
    }
    mysqli_stmt_close( $stmt );
}



?>

<html>
<head>
    <title>TA: Tickets Details</title>
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
        <div class="module-title">Tickets purchased by <?php echo $first_name . " " . $last_name ?></div>
        <div class="module-body">
            <?php
                echo "Order number: " . $order_id . "<br>";
                echo "Ordered on : " . $order_date . "<br>";
                echo "Going to: " . $event_name . "<br>";
                echo "Date of event: "  . $event_start_date . "<br>";
                
                if ( $stmt = mysqli_prepare( $connection, $preparedSeatQuery ) ) {
                    mysqli_stmt_bind_param( $stmt, 'i', $order_id );
                    if ( mysqli_stmt_execute( $stmt ) ) {
                        mysqli_stmt_bind_result( $stmt, $row_id, $seat );
                        echo "Your Seats: <br>";
                        while ( mysqli_stmt_fetch( $stmt ) ) {
                            echo "Row: " . $row_id . "   Seat: " . $seat . "<br>";
                        }

                    }
                    mysqli_stmt_close( $stmt );
                }

            ?>
        </div>
        </div>
    </div>
</body>
</html>

