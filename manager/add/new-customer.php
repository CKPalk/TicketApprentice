
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

$first_name     = $_POST["firstname"];
$last_name      = $_POST["lastname"];
$email          = $_POST["email"];
$address_line_1 = $_POST["address_line_1"];
$address_line_2 = $_POST["address_line_2"];
$city           = $_POST["city"];
$state          = $_POST["state"];
$zipcode        = $_POST["zipcode"];
$country        = $_POST["country"];



// Insert into Customers table here
$preparedCustomerInsert = "
    INSERT INTO Customers ( first_name, last_name, email, address_id ) 
        VALUES ( ?, ?, ?, ? );
";

$preparedAddressInsert = "
    INSERT INTO Addresses ( address_line_1, address_line_2, city, state, zipcode, country ) 
        VALUES ( ?, ?, ?, ?, ?, ? );
";

$preparedAddressQuery = "
    SELECT address_id FROM Addresses 
        WHERE address_line_1=? AND address_line_2=? AND city=? AND state=? AND zipcode=? AND country=?;
";

$preparedCustomerQuery = "
    SELECT customer_id FROM Customers
        WHERE first_name=? AND last_name=? AND email=? AND address_id=?;
";

?>

<html>
<head>
    <title>TA: New Customer</title>
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
        <div class="module-title">Add New Customer</div>
        <div class="module-body">
        <div style="text-align: center; width: 100%;">
            <?php
            if ( $stmt = mysqli_prepare( $connection, $preparedAddressInsert ) ) {
                mysqli_stmt_bind_param( $stmt, 'ssssis', $address_line_1, $address_line_2, $city, $state, $zipcode, $country); 
                if ( mysqli_stmt_execute( $stmt ) ) {
                echo "<div class='stmt-success'>1. Address Prepared Statement Passed</div>";
                    mysqli_stmt_close( $stmt );
                    if ( $stmt = mysqli_prepare( $connection, $preparedAddressQuery ) ) {
                    echo "<div class='stmt-success'>2. Address Inserted</div>";
                        mysqli_stmt_bind_param( $stmt, 'ssssis', $address_line_1, $address_line_2, $city, $state, $zipcode, $country);
                        if ( mysqli_stmt_execute( $stmt ) ) {
                            echo "<div class='stmt-success'>3. Address Entry Found</div>";
                            mysqli_stmt_bind_result( $stmt, $address_id );
                            mysqli_stmt_fetch( $stmt );
                            mysqli_stmt_close( $stmt );
                            if( $stmt = mysqli_prepare( $connection, $preparedCustomerInsert ) ) {
                                echo "<div class='stmt-success'>4. Customer Prepared Statement Passed</div>";
                                mysqli_stmt_bind_param( $stmt, 'sssi', $first_name, $last_name, $email, $address_id);
                                if ( mysqli_stmt_execute( $stmt ) ) {
                                    echo "<div class='stmt-success'>5. Customer Inserted</div>";
                                    mysqli_stmt_close( $stmt );
                                    if ( $stmt = mysqli_prepare( $connection, $preparedCustomerQuery ) ) {
                                        mysqli_stmt_bind_param( $stmt, 'sssi', $first_name, $last_name, $email, $address_id );
                                        if ( mysqli_stmt_execute( $stmt ) ) {
                                            mysqli_stmt_bind_result( $stmt, $customer_id );
                                            if ( !mysqli_stmt_fetch( $stmt ) ) {
                                                print "<div class='stmt_failed'>Customer Query Execution Failed</div>";
                                            }
                                        }
                                    }
                                }
                                else { echo "<div class='stmt_failed'>Customer Insert Execution Failed</div>"; }
                            }
                            else { echo "<div class='stmt-failed'>Customer Prepared Statement Failed</div>"; }
                        }
                        else { echo "<div class='stmt-failed'>Address Query Execution Failed</div>"; }
                    }
                }
                else { echo "<div class='stmt-failed'>Address Insert Execution Failed</div>"; }
            }
            else { echo "<div class='stmt-failed'>Address Insert Prepared Statement Failed</div>"; }
            ?>

            </br>
            </br>
            <a id="details-link" href="../view/customer-details.php?customer_id=<?php echo $customer_id; ?>">View your new customers detailed view</a>
            </div>
            </div>
        </div>
    </div>
</body>
</html>

