
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );


$event_name                 = $_POST["event_name"];
$event_start_date           = $_POST["event_start_date"] . " " . $_POST["event_start_time"];
$sale_date                  = $_POST["sale_date"];
$venue_id                   = $_POST["venue_id"];
$performer_id               = $_POST["performer_id"];
$event_category_code        = $_POST["event_category_code"];

$row_count                  = $_POST["row_count"];
$front_row_ticket_price     = $_POST["front_row_ticket_price"];
$last_row_ticket_price      = $_POST["last_row_ticket_price"];


// Insert into Events table here
$preparedEventInsert = "
    INSERT INTO Events ( event_name, event_start_date, ticket_start_sale_date, venue_id, performer_id, event_category_code ) 
        VALUES ( ?, ?, ?, ?, ?, ? );
";

$preparedEventQuery = "
    SELECT event_id 
    FROM Events 
    WHERE 
        event_name=? AND 
        event_start_date=? AND 
        ticket_start_sale_date=? AND 
        venue_id=? AND 
        performer_id=? AND 
        event_category_code=?
    ;
";

$preparedVenueRowsInsert = "
    INSERT INTO Venue_Rows ( event_id, venue_id, row_id, row_ticket_price )
        VALUES ( ?, ?, ?, ? );
";

?>

<html>
<head>
    <title>TA: New Event</title>
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
        <div class="module-title">New Event Information</div>
        <div class="module-body centered">
            <?php
            mysqli_stmt_close( $stmt );
            if( $stmt = mysqli_prepare( $connection, $preparedEventInsert ) ) {
                print "<div class='stmt-success'>Event Prepared Statement Passed</div>";
                mysqli_stmt_bind_param( $stmt, 'sssiii', $event_name, $event_start_date, $sale_date, $venue_id, $performer_id, $event_category_code );
                if ( mysqli_stmt_execute( $stmt ) ) {
                    print "<div class='stmt-success'>Event Added</div>";

                    mysqli_stmt_close( $stmt );
                    // Get Event ID
                    if ( $stmt = mysqli_prepare( $connection, $preparedEventQuery ) ) {
                        print "<div class='stmt-success'>Event ID Prepared Statment Passed</div>";
                        mysqli_stmt_bind_param( $stmt, 'sssiii', $event_name, $event_start_date, $sale_date, $venue_id, $performer_id, $event_category_code );
                        if( mysqli_stmt_execute( $stmt ) ) {
                            print "<div class='stmt-success'>Event ID Queried</div>";
                            mysqli_stmt_bind_result( $stmt, $event_id );
                            mysqli_stmt_fetch( $stmt );
                        } else { print "<div class='stmt-failed'>Event ID Query Failed</div>"; }
                    } else { print "<div class='stmt-failed'>Event ID Query Prepared Statement Failed</div>"; }

                    mysqli_stmt_close( $stmt );

                    if ( $stmt = mysqli_prepare( $connection, $preparedVenueRowsInsert ) ) {
                        print "<div class='stmt-success'>Venue Rows Prepared Statement Passed</div>";

                        // Inserting rows with appropriate pricing to venue rows relation
                        $row_price_difference = $front_row_ticket_price - $last_row_ticket_price;
                        $insertedVenueRows = true;
                        for ( $row_index = 1; $row_index <= $row_count; $row_index++ ) {
                            $row_price = round($front_row_ticket_price - ( (($row_index - 1) / ($row_count - 1)) * $row_price_difference ), 2);
                            mysqli_stmt_bind_param( $stmt, 'iiid', $event_id, $venue_id, $row_index, $row_price );
                            if ( !mysqli_stmt_execute( $stmt ) ) {
                                $insertedVenueRows = false;
                            }
                        }
                        mysqli_stmt_close( $stmt );
                        if ( $insertedVenueRows ) {
                            print "<div class='stmt-success'>Venue Rows Inserted</div>";
                        }
                        else {
                            print "<div class='stmt-failed'>Venue Rows Insert Execution Failed</div>";
                        } 
                    } else { print "<div class='stmt-failed'>Venue Rows Prepared Statement Failed</div>"; }
                }
                else {
                    print "<div class='stmt-failed'>Event Insert Execution Failed</div>";
                }
                mysqli_stmt_close( $stmt );
            }
            else {
                print "<div class='stmt-failed'>Prepared Statement Failed</div>";
            }
            ?>
            </br>
            </br>
            <a id="details-link" href="../view/event-details.php">View New Event Details</a>
            </div>
        </div>
    </div>
</body>
</html>

