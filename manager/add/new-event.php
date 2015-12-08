
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

$performer_id = 1;


// Insert into Events table here
$preparedEventInsert = "
    INSERT INTO Events ( event_name, event_start_date, ticket_start_sale_date, venue_id, performer_id, event_category_code ) 
        VALUES ( ?, ?, ?, ?, ?, ? );
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

