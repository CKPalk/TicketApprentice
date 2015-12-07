
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
    ) VALUES ( ?, ?, ?, ? );
";

?>

<html>
<head>
    <title>TA: New Performer</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css?v=<?=time();?>">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css?v=<?=time();?>">
</head>
<body>
<div id="body_container">
<div id="middle_container">
    <div class="module">
        <div class="module-title">New Performer Information</div>
        <div class="module-body centered">
            <?php
            if( $stmt = mysqli_prepare( $connection, $preparedPerformerInsert ) ) {
                print "<div class='stmt-success'>Performer Prepared Statement Passed</div>";
                mysqli_stmt_bind_param( $stmt, 'ssss', $firstname, $lastname, $dob, $gender );

                if ( mysqli_stmt_execute( $stmt ) ) {
                    print "<div class='stmt-success'>Performer Added</div>";
                }
                else {
                    print "<div class='stmt-failed'>Performer Insert Execution Failed</div>";
                }

                mysqli_stmt_close( $stmt );
            }
            else {
                print "<div class='stmt-failed'>Prepared Statement Failed</div>";
            }
            ?>
            </br>
            </br>
            <a id="details-link" href="../view/performer-details.php">View New Performer Details</a>
            </div>
        </div>
    </div>
</body>
</html>

