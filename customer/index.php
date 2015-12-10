
<?php
// Make connection to server
include( '../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ../support/connection-error.html' );
?>

<html>
<head>
    <title>Customer Home</title>
    <link rel="stylesheet" type="text/css" href="../support/manager.css?v=<?=time();?>">
    <link rel="stylesheet" type="text/css" href="../support/global.css?v=<?=time();?>">
    <META HTTP-EQUIV="refresh" CONTENT="15">
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
            <div class="module-title">Search For Events</div>
            <div class="module-body" style="text-align: center">
                <form method="post" action="search/event-search.php">
                    Search by Venue:
                    <select name="venue_id">
                        <option value="" selected>Optional Venue Search</option>
                        <?php $preparedVenueQuery = "SELECT venue_id, venue_name FROM Venues;";
                        if ( $stmt = mysqli_prepare( $connection, $preparedVenueQuery ) ) { 
                            if ( mysqli_stmt_execute( $stmt ) ) { 
                                mysqli_stmt_bind_result( $stmt, $venue_id, $venue_name ); 
                                while ( mysqli_stmt_fetch( $stmt ) ) { 
                                    echo "<option value='" . $venue_id . "'>" . $venue_name . "</option>";
                                } 
                            } 
                            mysqli_stmt_close( $stmt );
                        } ?>
                    </select><br>

                    Search by Performer:
                    <select name="performer_id">
                        <option value="" selected>Optional Performer Search</option>
                        <?php $preparedPerformerQuery = "SELECT performer_id, CONCAT(first_name, ' ', last_name) AS performer_name FROM Performers;";
                        if ( $stmt = mysqli_prepare( $connection, $preparedPerformerQuery ) ) { 
                            if ( mysqli_stmt_execute( $stmt ) ) { 
                                mysqli_stmt_bind_result( $stmt, $performer_id, $performer_name ); 
                                while ( mysqli_stmt_fetch( $stmt ) ) { 
                                    echo "<option value='" . $performer_id . "'>" . $performer_name . "</option>";
                                } 
                            } 
                            mysqli_stmt_close( $stmt );
                        } ?>
                    </select><br>

                    <input type="submit" value="Find Events"><br>
                    
                </form>
            </div>
        </div>
        <div class="module">
            <div class="module-title">Events Coming Up</div>
            <div class="module-body" style="text-align: center">
                <?php $preparedEventSortedQuery = "SELECT event_id, event_name, event_start_date FROM Events ORDER BY (event_start_date) LIMIT 10;";
                if ( $stmt = mysqli_prepare( $connection, $preparedEventSortedQuery ) ) { 
                    if ( mysqli_stmt_execute( $stmt ) ) { 
                        mysqli_stmt_bind_result( $stmt, $event_id, $event_name, $event_start_date ); 
                        while ( mysqli_stmt_fetch( $stmt ) ) { 
                            echo "<a class='list-link' href='view/event-details.php?event_id=" . $event_id . "'>" . 
                                $event_name . 
                                "<span style='float:right; font-size:14px;'>" . $event_start_date . "</span>" .
                            "</a>";
                        } 
                    } else { print "Event sorted query execution failed."; }
                    mysqli_stmt_close( $stmt );
                } else { print "Event sorted query prepared statement failed."; } ?>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
