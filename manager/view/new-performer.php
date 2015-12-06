
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );


$firstname  = $_POST["firstname"];
$lastname   = $_POST["lastname"];
$dob        = $_POST["dob"];
$gender     = $_POST["sex"];


// Insert into Performers table here
$preparedPerformerInsert = "
    INSERT INTO Performers (
        first_name,
        last_name,
        date_of_birth,
        gender
    ) VALUES (
        ?,
        ?,
        ?,
        ?
    );
";

?>

<html>
<head>
    <title>TA: New Performer</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css">
</head>
<body>
<div id="body_container">
<div id="middle_container">
    <div class="module">
        <div class="module-title">New Performer Information</div>
        <div class="module-body">
            <?php
            if( $stmt = mysqli_prepare( $connection, $preparedPerformerInsert ) ) {
                mysqli_stmt_bind_param( $stmt, 'ssss', $firstname, $lastname, $dob, $gender );

                if ( mysqli_stmt_execute( $stmt ) ) {
                    print "Insert succeeded";
                }
                else {
                    print "Insert failed";
                }

                mysqli_stmt_close( $stmt );
            }
            else {
                print "Prepared statement failed";
            }

?>

            </div>
        </div>
    </div>
</body>
</html>

