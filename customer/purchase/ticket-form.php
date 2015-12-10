
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

$event_id = $_GET['event_id'];

$preparedEventQuery = "SELECT event_name FROM Events;";
if ( $stmt = mysqli_prepare( $connection, $preparedEventQuery ) ) { 
    if ( mysqli_stmt_execute( $stmt ) ) { 
        mysqli_stmt_bind_result( $stmt, $event_name ); 
        mysqli_stmt_fetch( $stmt );
    } 
    mysqli_stmt_close( $stmt );
}

$preparedVenueRowsQuery = "
    SELECT row_id, row_ticket_price 
    FROM Venue_Rows 
    WHERE event_id=?;
";



?>

<html>
<head>
    <title>TA: Tickets</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css">
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
        <div class="form-module">
            <div class="module-title">Purchase Tickets for <?php echo $event_name ?></div>
            <div class="module-body" style="text-align: center">
            <form class="add-form" action="ticket-comfirmation.php?event_id=<?php echo $event_id ?>" method="post" id="new-tickets">

                    Your First Name:
                    <input type="text" name="customer_first" placeholder="Cameron"><br> 
                    Your Last Name:
                    <input type="text" name="customer_last" placeholder="Palk"><br> 
                    Email:
                    <input type="email" name="email" placeholder="cpalk@uoregon.edu"><br>

                    Address:
                    <input type="text" name="address_line_1" placeholder="1776 E 13th Ave">
                    <input type="text" name="address_line_2" placeholder="optional"> <br> 
                    City:
                    <input type="text" name="city" placeholder="Eugene"> <br> 
                    State:
                    <input type="text" name="state" placeholder="Oregon"> <br> 
                    Zipcode:
                    <input type="number" name="zipcode" placeholder="97403"> <br> 
                    Country:
                    <input type="text" name="country" placeholder="USA"> <br>

                    Number of Tickets:
                    <input type="number" name="ticket_count" value="1"><br>

                    Select Row ( only rows with available seats ):
                    <select name="row_id">
                    <?php
                        if ( $stmt = mysqli_prepare( $connection, $preparedVenueRowsQuery ) ) {
                            mysqli_stmt_bind_param( $stmt, 'i', $event_id );
                            if ( mysqli_stmt_execute( $stmt ) ) {
                                mysqli_stmt_bind_result( $stmt, $row_id, $row_ticket_price );
                                while ( mysqli_stmt_fetch( $stmt ) ) {
                                    echo "<option value='" . $row_id . "'>" . $row_id . " -- ( " . $row_ticket_price . " )</option>";
                                }
                            } else { print "<option>prepare failed</option>"; }
                            mysqli_stmt_close( $stmt );
                        } else { print "<option>prepare failed</option>"; }
                    ?>
                    </select><br>

                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
