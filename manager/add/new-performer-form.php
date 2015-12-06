
<html>
<head>
    <title>TA: New Performer</title>
    <link rel="stylesheet" type="text/css" href="../../support/global.css">
    <link rel="stylesheet" type="text/css" href="../../support/manager.css">
</head>
<body>
    <div id="body_container">
    <div id="middle_container">
        <div class="form-module">
            <div class="module-title">New Performer Form</div>
            <div class="module-body" style="text-align: center">
                <form class="add-form" action="../view/new-performer.php" method="post">
                    First Name:
                    <input type="text" name="firstname">

                    <br>

                    Last Name:
                    <input type="text" name="lastname"> 

                    <br>

                    Date of Birth:
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
