<html>
<head>
    <title>TA: New Performer</title>
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
            <div class="module-title">New Performer/Group Form</div>
            <div class="module-body" style="text-align: center">
                <form class="add-form" action="new-performer.php" method="post">
                    First Name/Group Name:
                    <input type="text" name="firstname">

                    <br>

                    Last Name ( optional ):
                    <input type="text" name="lastname"> 

                    <br>

                    Date of Birth ( optional ):
                    <input type="date" name="dob">

                    <br>

                    <input type="radio" name="sex" value="M"> Male <br>
                    <input type="radio" name="sex" value="F"> Female <br>
                    <input type="radio" name="sex" value="U" checked> Unknown <br>

                    <br>

                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
