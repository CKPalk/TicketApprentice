
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

$venue_name        = $_POST["venue_name"];
$venue_seat_capacity = $_POST["venue_seat_capacity"];
$address_line_1    = $_POST["address_line_1"];
$address_line_2    = $_POST["address_line_2"];
$city              = $_POST["city"];
$state             = $_POST["state"];
$zipcode           = $_POST["zipcode"];
$country           = $_POST["country"];



// Insert into Venues table here
$preparedVenueInsert = "
    INSERT INTO Venues (
        venue_name,
        venue_seat_capacity,
        address_id
    ) VALUES ( ?, ?, ? );
";

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


?>

<html>
<head>
    <title>TA: New Venue</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css">
</head>
<body>
<div id="body_container">
<div id="middle_container">
    <div class="module">
        <div class="module-title">New Venue Information</div>
        <div class="module-body">
            <?php
            if ( $stmt = mysqli_prepare( $connection, $preparedAddressInsert ) ) {
                mysqli_stmt_bind_param( $stmt, 'ssssis', 
                    $address_line_1, 
                    $address_line_2,
                    $city,
                    $state,
                    $zipcode,
                    $country
                );

                if ( mysqli_stmt_execute( $stmt ) ) {
                    mysqli_stmt_close( $stmt );
                    if ( $stmt = mysqli_prepare( $connection, $preparedAddressQuery ) ) {
                        mysqli_stmt_bind_param( $stmt, 'ssssis',
                            $address_line_1, 
                            $address_line_2,
                            $city,
                            $state,
                            $zipcode,
                            $country
                        );
                        if ( mysqli_stmt_execute( $stmt ) ) {
                            mysqli_stmt_bind_result( $stmt, $address_id );
                            mysqli_stmt_fetch( $stmt );
                            mysqli_stmt_close( $stmt );
                            if( $stmt = mysqli_prepare( $connection, $preparedVenueInsert ) ) {
                                mysqli_stmt_bind_param( $stmt, 'sii',
                                    $venue_name,
                                    $venue_seat_capacity,
                                    $address_id
                                );
                                if ( mysqli_stmt_execute( $stmt ) ) {
                                    echo "<div class='stmt_success' id='test'>Venue Insert Successful</div>";
                                }
                                else { echo "<div class='stmt_failed'>Venue Insert Execution Failed</div>"; }
                                echo "<div class='stmt_success'>Venue Insert Prepared Statement Successful</div>";
                            }
                            else { echo "Venue Insert Prepared Statement Failed<br>"; }
                            echo "<div class='stmt-success'>Address Query Execution Success</div>";
                        }
                        else { echo "Address Query Execution Failed<br>"; }
                        echo "<div class='stmt-success'>Address Query Prepared Statement Success</div>";
                    }
                    else { echo "Address Query Prepared Statement Failed<br>"; }
                    echo "<div class='stmt-success'>Address Insert Execution Success</div>";
                }
                else { echo "Address Insert Execution Failed<br>"; }
                echo "<div class='stmt-success'>Address Insert Prepared Statement Success</div>";
            }
            else { echo "Address Insert Prepared Statement Failed<br>"; }
            ?>
            </div>
        </div>
    </div>
</body>
</html>

