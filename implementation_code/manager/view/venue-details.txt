
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

if ( isset( $_GET['venue_id'] ) )
    $venue_id = $_GET['venue_id'];
else
    print 'Nothing found for venue id';



// Insert into Venues table here
$preparedVenueQuery = "
    SELECT venue_name, venue_seat_capacity, address_id FROM Venues WHERE
        venue_id=?
    ;
";

$preparedAddressQuery = "
    SELECT address_line_1, address_line_2, city, state, zipcode, country FROM Addresses WHERE address_id=?;
";

// Get information from passed venue id
if ( $stmt = mysqli_prepare( $connection, $preparedVenueQuery ) ) {
    mysqli_stmt_bind_param( $stmt, 'i', $venue_id ); 
    if ( mysqli_stmt_execute( $stmt ) ) {
        mysqli_stmt_bind_result( $stmt, $venue_name, $venue_seat_capacity, $address_id );
        if ( mysqli_stmt_fetch( $stmt ) ) {
            
            mysqli_stmt_close( $stmt );
            if ( $stmt = mysqli_prepare( $connection, $preparedAddressQuery ) ) {
                mysqli_stmt_bind_param( $stmt, 'i', $address_id );
                if ( mysqli_stmt_execute( $stmt ) ) {
                    mysqli_stmt_bind_result( $stmt, $address_line_1, $address_line_2, $city, $state, $zipcode, $country );
                    if ( !mysqli_stmt_fetch( $stmt ) ) {
                        print "Address search failed";
                    }
                }
            }
        }
        else {
            print "Venue search failed";
        }

    }
}


?>

<html>
<head>
    <title>TA: Venue Details</title>
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
        <div class="module-title"><?php echo $venue_name ?></div>
        <div class="module-body">
            Seat capacity: <?php echo $venue_seat_capacity ?></br></br>
            Address: </br> 
                <?php echo $address_line_1 . "<br>";
                if ( !empty( $address_line_2 ) ) 
                    echo $address_line_2 . "<br>"; 
                echo $city . ", " . $state . ", " . $zipcode . "<br>" . $country; ?>
        </div>
        </div>
    </div>
</body>
</html>

