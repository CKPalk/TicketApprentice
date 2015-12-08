
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

$venue_id       = $_POST["venue_id"];
$performer_id   = $_POST["performer_id"];


$preparedEventQuery = "SELECT event_id, event_name, event_start_date FROM Events";

if ( !empty( $venue_id ) || !empty( $performer_id ) ) {
    $preparedEventQuery .= " WHERE ";

    if ( !empty( $venue_id ) ) {
        $preparedEventQuery .= "venue_id=?";
        if ( !empty( $performer_id ) )
            $preparedEventQuery .= " AND ";
    }

    if ( !empty( $performer_id ) )
        $preparedEventQuery .= "performer_id=?";
}

$preparedEventQuery .= ";";



?>

<html>
<head>
    <title>TA: Event Search</title>
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
        <div class="module-title">Events Matching Search Criteria:</div>
            <div class="module-body" style="text-align: center">
                <?php 
                if ( $stmt = mysqli_prepare( $connection, $preparedEventQuery ) ) {
                    if ( !empty( $venue_id ) || !empty( $performer_id ) ) {
                        if ( !empty( $venue_id ) && !empty( $performer_id ) ) {
                            mysqli_stmt_bind_param( $stmt, 'ii', $venue_id, $performer_id );
                        }
                        else if ( !empty( $venue_id ) ) {
                            mysqli_stmt_bind_param( $stmt, 'i', $venue_id );
                        }
                        else if ( !empty( $performer_id ) ) {
                            mysqli_stmt_bind_param( $stmt, 'i', $performer_id );
                        }
                    }
                    if ( mysqli_stmt_execute( $stmt ) ) { 
                        mysqli_stmt_bind_result( $stmt, $event_id, $event_name, $event_start_date ); 
                        while ( mysqli_stmt_fetch( $stmt ) ) { 
                            echo 
                            "<a class='list-link' href='../view/event-details.php?event_id=" . $event_id . "'>" 
                                . $event_name . "<span style='float:right;font-size:12px; color:#EEE;'>Begins: " . $event_start_date . 
                            "</a>";
                        } 
                    } 
                    else { print "Execute failed."; }
                    mysqli_stmt_close( $stmt );
                } 
                else { print "Prepared failed."; }

                ?>
            </div>
        </div>
    </div>
</body>
</html>
