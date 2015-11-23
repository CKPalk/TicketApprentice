
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
            <div class="module-title">All Events</div>
            <div class="module-body">
            Cameron
            <ul>
            <?php 
                echo "<li>" . $username . "@" . $server . ":" . $port . "</li>";
                echo "<li>" . $database . "</li>";
            ?>  
            </ul>
            </div>
        </div>

        <div class="module">
            <div class="module-title">title</div>
            <div class="module-body">
                Content.
            </div>
        </div>

    </div>
    </div>
</body>
</html>
