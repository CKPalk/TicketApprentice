
<?php
// Make connection to server
include( '../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ../support/connection-error.html' );
?>

<html>
<head>
    <title>Manager Home</title>
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
            <div class="module-title">Add New Content</div>
            <div class="module-body" style="text-align: center">
                <a href="add/new-event-form.php">Add Event</a>
                <a href="add/new-customer-form.php">Add Customer</a>
                <a href="add/new-venue-form.php">Add Venue</a>
                <a href="add/new-performer-form.php">Add Performer/Group</a>
            </div>
        </div>
        <div class="module">
            <div class="module-title">All Events</div>
            <div class="module-body">
            <?php
                $allEventsQuery = "SELECT event_id, event_name FROM Events";
                if ( $stmt = mysqli_prepare( $connection, $allEventsQuery ) ) {
                    mysqli_stmt_execute( $stmt );
                    mysqli_stmt_bind_result( $stmt, $event_id, $event_name );

                    while ( mysqli_stmt_fetch( $stmt ) ) {
                        echo "<a class='list-link' href='view/event-details.php?event_id=" . $event_id . "'>" . $event_name . "<img src='../support/images/info.png' style='float:right;height:20px;width:20px;padding:4px;'></a>";
                    }

                    mysqli_stmt_close( $stmt );
                }
                else {
                    echo "Failed to find events.";
                }
            ?>
            </div>
        </div>
        <div class="module">
            <div class="module-title">All Venues</div>
            <div class="module-body">
            <?php
                $allVenuesQuery = "SELECT venue_id, venue_name FROM Venues";
                if ( $stmt = mysqli_prepare( $connection, $allVenuesQuery ) ) {
                    mysqli_stmt_execute( $stmt );
                    mysqli_stmt_bind_result( $stmt, $venue_id, $venue_name );

                    while ( mysqli_stmt_fetch( $stmt ) ) {
                        echo "<a class='list-link' href='view/venue-details.php?venue_id=" . $venue_id . "'>" . $venue_name . "<img src='../support/images/info.png' style='float:right;height:20px;width:20px;padding:4px;'></a>";
                    }

                    mysqli_stmt_close( $stmt );
                }
                else {
                    echo "Failed to find venues.";
                }
            ?>
            </div>
        </div>

        <div class="module">
            <div class="module-title">Customer Search</div>
            <div class="module-body">
                <form action="search/customer-search.php" method="get">
                    Customer Name [ Fuzzy Search ]:
                    <input type="text" name="customer-name-search" placeholder='"Cameron" ( All customers named Cameron )'><br>
                    <input type="submit" value="Search">
                </form>
            </div>
        </div>

    </div>
    </div>
</body>
</html>
