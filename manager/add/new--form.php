
<?php
// Make connection to server
include( '../support/connectionCredentials.php' );

$connection = mysqli_connect
    ( $server, $username, $password, $database, $port );

if ( !$connection ) header( 'Location: ../support/connection-error.html' );
?>

<html>
<head>
    <title>TITLE HERE</title>
    <link rel="stylesheet" type="text/css" href="../support/global.css">
    <link rel="stylesheet" type="text/css" href="../support/manager.css">
</head>
<body>
    <div id="body_container">
    <div id="middle_container">
        <div class="module">
            <div class="module-title">New ___ Form</div>
            <div class="module-body" style="text-align: center">
                <form>
                 


            </div>
        </div>
    </div>
</body>
</html>
