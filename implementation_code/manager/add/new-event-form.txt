
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );
?>

<html>
<head>
    <title>TA: New Event</title>
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
            <div class="module-title">New Event Form</div>
            <div class="module-body" style="text-align: center">
                <form class="add-form" action="new-event.php" method="post" id="new-event">
                    Event Name:
                    <input type="text" name="event_name" placeholder="Valero Alamo Bowl - Oregon v TCU"><br> 
                    Event Starting Date \ Time:
                    <input type="date" name="event_start_date" style="display:inline-block; width:auto;">
                    <input type="time" name="event_start_time" style="display:inline-block; width:auto; float:right; text-align: right;"><br> 
                    Ticket Sales Starting Date:
                    <input type="date" name="sale_date"><br> 
                    Venue: <span style="font-size: 16px; line-height: 20px; margin: 2px; color: #7f8c8d; float: right; padding-top: 4px;">Add more from the Manager Menu</span>
                    <select name="venue_id" form="new-event">
                        <option value="" disabled selected>Required</option>
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

                    Number of Rows to Sell Tickets for:
                    <input type="number" name="row_count" style="width: 100px;" min="0" placeholder="100"><br>
                    <div class="grader-note">Using linearly increasing seat pricing so you don't have to edit every rows price.</div>
                    Front Row Ticket Price $
                    <input type="number" name="front_row_ticket_price" style="width: 100px;" min="0" placeholder="300"><br>
                    Last Row Ticket Price $
                    <input type="number" name="last_row_ticket_price" style="width: 100px;" min="0" placeholder="20"><br>
                    


                    Performer: <span style="font-size:16px; line-height:20px; margin:2px; color:#7f8c8d; float:right; padding-top:4px;">Add more from the Manager Menu</span>
                    <select name="performer_id" form="new-event">
                        <option value="" selected>Required</option>
                        <?php $preparedPerformerQuery = "SELECT performer_id, first_name, last_name FROM Performers;";
                        if ( $stmt = mysqli_prepare( $connection, $preparedPerformerQuery ) ) {
                            if ( mysqli_stmt_execute( $stmt ) ) {
                                mysqli_stmt_bind_result( $stmt, $performer_id, $first_name, $last_name );
                                while ( mysqli_stmt_fetch( $stmt ) ) {
                                    echo "<option value='" . $performer_id . "'>" . $first_name;
                                    if ( !empty( $last_name ) ) echo " " . $last_name;
                                    echo "</option>";
                                }
                            }
                            mysqli_stmt_close( $stmt );
                        } ?>
                    </select><br>
                    Category:
                    <select name="event_category_code" form="new-event">
                        <?php if ( $stmt = mysqli_prepare( $connection, "SELECT event_category_code, event_category_name FROM Event_Categories;" ) ) {
                            if ( mysqli_stmt_execute( $stmt ) ) {
                                mysqli_stmt_bind_result( $stmt, $event_category_code, $event_category_name );
                                while ( mysqli_stmt_fetch( $stmt ) ) {
                                    echo "<option value='" . $event_category_code . "'>" . $event_category_name . "</option>";
                                }
                            }
                        } ?>
                    </select><br>

                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
