
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
    <link rel="stylesheet" type="text/css" href="../support/global.css">
    <link rel="stylesheet" type="text/css" href="../support/manager.css">
</head>
<body><!--
    <div id="menu_container">
        <a href="_blank">link1</a>
        <a href="_blank">link2</a>
        <a href="_blank">link3</a>
        <a href="_blank">link4</a>
    </div>
 --><div id="body_container">
    <div id="middle_container">
        <div class="module">
            <div class="module-title">Add New Content</div>
            <div class="module-body" style="text-align: center">
                <a href="add/new-event-form.php">Add Event</a>
                <a href="add/new-customer-form.php">Add Customer</a>
                <a href="add/new-venue-form.php">Add Venue</a>
                <a href="add/new-performer-form.php">Add Performer</a>
            </div>
        </div>
        <div class="module">
            <div class="module-title">Event Detailed View</div>
            <div class="module-body">
            <?php
                $allEventsQuery = "SELECT event_id, event_name FROM Events";
                if ( $stmt = mysqli_prepare( $connection, $allEventsQuery ) ) {
                    mysqli_stmt_execute( $stmt );
                    mysqli_stmt_bind_result( $stmt, $event_id, $event_name );

                    while ( mysqli_stmt_fetch( $stmt ) ) {
                        echo "<li>" . $event_id . ": " . $event_name . "</li>";
                    }
                }
                else {
                    echo "Failed to find events.";
                }

            ?>
            </div>
        </div>

        <div class="module">
            <div class="module-title">Customer Search</div>
            <div class="module-body">
                
            </div>
        </div>

    </div>
    </div>
</body>
</html>
