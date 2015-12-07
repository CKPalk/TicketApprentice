
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
            <div class="module-title">New Venue Form</div>
            <div class="module-body" style="text-align: center">
                <form class="add-form" action="new-venue.php" method="post">
                    Venue Name:
                    <input type="text" name="venue_name" placeholder="Matthew Knight Arena">

                    <br>

                    Venue Seat Capacity:
                    <input type="number" name="venue_seat_capacity" min="0" placeholder="12364"> 

                    <br>

                    Address:
                    <input type="text" name="address_line_1" placeholder="1776 E 13th Ave">
                    <input type="text" name="address_line_2" placeholder="optional">

                    <br>

                    City:
                    <input type="text" name="city" placeholder="Eugene">

                    <br>

                    State:
                    <input type="text" name="state" placeholder="Oregon">

                    <br>

                    Zipcode:
                    <input type="number" name="zipcode" placeholder="97403">

                    <br>

                    Country:
                    <input type="text" name="country" placeholder="USA">

                    <br>

                    <input type="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
