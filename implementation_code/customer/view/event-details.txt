
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

if ( isset( $_GET['event_id'] ) )
    $event_id = $_GET['event_id'];
else
    print 'Nothing found for event id';


$preparedEventQuery = "
    SELECT E.event_name, E.event_start_date, E.ticket_start_sale_date, E.venue_id, E.performer_id, EC.event_category_name, EC.associated_color_hex
    FROM Events E JOIN Event_Categories EC USING ( event_category_code )
        WHERE E.event_id=?;
";

$preparedVenueQuery = "
    SELECT 
        Venues.venue_name, Venues.venue_seat_capacity, Venues.address_id, 
        Addresses.address_line_1, Addresses.address_line_2, Addresses.city, Addresses.state, Addresses.zipcode, Addresses.country 
    FROM Venues JOIN Addresses USING ( address_id ) 
        WHERE Venues.venue_id=?;
";

$preparedPerformerQuery = "
    SELECT first_name, last_name, date_of_birth, gender FROM Performers
        WHERE performer_id=?;
";

// EVENT
if ( $stmt = mysqli_prepare( $connection, $preparedEventQuery ) ) {
    mysqli_stmt_bind_param( $stmt, 'i', $event_id );
    if ( mysqli_stmt_execute( $stmt ) ) {
        mysqli_stmt_bind_result( $stmt, $event_name, $event_start_date, $ticket_start_sale_date, $venue_id, $performer_id, $event_category_name, $associated_color_hex );
        mysqli_stmt_fetch( $stmt );
        mysqli_stmt_close( $stmt );
    } else { print "Event query execution failed."; }
} else { print "Event query prepared statement failed."; }

// VENUE
if ( $stmt = mysqli_prepare( $connection, $preparedVenueQuery ) ) {
    mysqli_stmt_bind_param( $stmt, 'i', $venue_id );
    if ( mysqli_stmt_execute( $stmt ) ) {
        mysqli_stmt_bind_result( $stmt, $venue_name, $venue_seat_capacity, $address_id, $address_line_1, $address_line_2, $city, $state, $zipcode, $country );
        mysqli_stmt_fetch( $stmt );
        mysqli_stmt_close( $stmt );
    } else { print "Venue query execution failed."; }
} else { print "Venue query prepared statement failed."; }

?>

<html>
<head>
    <title>TA: Event Details <!-- Change to event name --></title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css?v=<?=time();?>">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css?v=<?=time();?>">
</head>
<body>
<!-- HEADER BAR 8 lines -->
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
        <div class="module-title">
            Name: <?php echo $event_name ?>
            <span style="float:right; padding: 4px 8px 4px 8px; margin-top: -4px; border-radius: 6px; background-color: #<?php echo $associated_color_hex; ?>;">
                <?php echo $event_category_name ?>
            </span>
        </div>
        <div class="module-body">
            <!-- Purchase tickets button -->
            <a href="../purchase/ticket-form.php?event_id=<?php echo $event_id ?>" style="float:right">Look for Tickets</a>

            Venue: <?php echo $venue_name ?>
            </br></br>
            Seat capacity: <?php echo $venue_seat_capacity ?>
            </br></br>
            Address: </br> 
                <?php echo $address_line_1 . "<br>";
                if ( !empty( $address_line_2 ) ) 
                    echo $address_line_2 . "<br>"; 
                echo $city . ", " . $state . ", " . $zipcode . "<br>" . $country; ?>
            </br></br>
            
            
        </div>
        </div>
    </div>
</body>
</html>

