
<?php
// Make connection to server
include( '../../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ~/public_html/TicketApprentice/support/connection-error.html' );

$customer_name_search = "%" . $_GET["customer-name-search"] . "%";

?>

<html>
<head>
    <title>TA: Customer Search</title>
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
        <div class="module-title">Customer Search: '<?php echo $_GET["customer-name-search"]; ?>'</div>
            <div class="module-body" style="text-align: center">
                <?php 

                $preparedCustomerQuery = "SELECT customer_id, first_name, last_name FROM Customers WHERE CONCAT(first_name, ' ', last_name) LIKE ?;";
                if ( $stmt = mysqli_prepare( $connection, $preparedCustomerQuery ) ) { 
                    mysqli_stmt_bind_param( $stmt, 's', $customer_name_search );
                    if ( mysqli_stmt_execute( $stmt ) ) { 
                        mysqli_stmt_bind_result( $stmt, $customer_id, $first_name, $last_name ); 
                        while ( mysqli_stmt_fetch( $stmt ) ) { 
                            echo "<a class='list-link' href='../view/customer-details.php?customer_id=" . $customer_id . "'>" . $first_name . " " . $last_name . "</a>";
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
