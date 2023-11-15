<?php

// connect to the database
$conn = mysqli_connect('localhost:3306', 'root', '123456', 'RailwaySystemWebsite');

// check connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}

//View 1

$RI = 'SELECT Routes.RouteID, Routes.Duration, Routes.Distance, Trains.TrainID, s1.Location AS StartStation, s1.Location AS EndStation FROM Routes JOIN Trains ON Routes.OperatingTrains = Trains.TrainID JOIN Stations s1 ON Routes.StartStation = s1.StationID JOIN Stations s2 ON Routes.EndStation = s2.StationID';
$RI2 = mysqli_query($conn, $RI);
$RouteInfo = mysqli_fetch_all($RI2, MYSQLI_ASSOC);


//View 2
$PassTrains = 'SELECT T.TrainID, MAX(R.Weight) AS MaximumWeight, SUM(R.PassengerCapacity) AS TotalPassengerCount, RT.RouteID, S1.Location AS StartStation, S2.Location AS EndStation 
                FROM Trains T 
                JOIN RailCars R ON T.BodyRailCarType = R.ModelNumber 
                JOIN Routes RT ON T.TrainID = RT.OperatingTrains 
                JOIN Stations S1 ON RT.StartStation = S1.StationID 
                JOIN Stations S2 ON RT.EndStation = S2.StationID 
                WHERE R.Category = "Passenger" 
                GROUP BY T.TrainID, RT.RouteID, S1.Location, S2.Location';

$PasseTrain = mysqli_query($conn, $PassTrains);
$PassengerTrains = mysqli_fetch_all($PasseTrain, MYSQLI_ASSOC);

//View 3

$RTC = 'SELECT r.RouteID, (SELECT COUNT(*) FROM Trains T JOIN Routes rt ON rt.OperatingTrains = t.TrainID WHERE rt.RouteID = r.RouteID) AS NumberOfTrains FROM Routes r';
$RTC2 = mysqli_query($conn, $RTC);
$RouteTrainCount = mysqli_fetch_all($RTC2, MYSQLI_ASSOC);

//View 4

$RCI = 'SELECT RailCars.ModelNumber, RailCars.ModelName FROM RailCars LEFT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType UNION SELECT RailCars.ModelNumber, RailCars.ModelName FROM RailCars RIGHT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType';
$RCI2 = mysqli_query($conn, $RCI);
$RailCarInfo = mysqli_fetch_all($RCI2, MYSQLI_ASSOC);

//View 5

$RS = 'SELECT * FROM (SELECT "Weekday" AS DayCategory, r.RouteID, r.StartStation, r.EndStation, rt.StartTime, rt.EndTime, rt.DayOfWeek FROM Routes r INNER JOIN RunningTimes rt ON r.RouteID = rt.RouteID WHERE rt.DayOfWeek IN ("Monday", "Tuesday", "Wednesday", "Thursday", "Friday") UNION SELECT "Weekend" AS DayCategory, r.RouteID, r.StartStation, r.EndStation, rt.StartTime, rt.EndTime, rt.DayOfWeek FROM Routes r INNER JOIN RunningTimes rt ON r.RouteID = rt.RouteID Where rt.DayOfWeek IN ("Saturday", "Sunday")) AS CombinedData';
$RS2 = mysqli_query($conn, $RS);
$RailCarInfo = mysqli_fetch_all($RS2, MYSQLI_ASSOC);

//View 6

$Long = 'SELECT RouteID, Distance
        FROM Routes
        ORDER BY Distance DESC';
$LongR = mysqli_query($conn, $Long);
$LongestRoutes = mysqli_fetch_all($LongR, MYSQLI_ASSOC);


//View 7

$Dest = 'SELECT DISTINCT U.Username, U.Location AS CurrentLocation, S.Location AS PossibleDestination FROM User U JOIN Routes R ON U.Location = (SELECT S.Location FROM Stations S WHERE S.StationID = R.StartStation) JOIN Stations S ON R.EndStation = S.StationID';
$DestRoute = mysqli_query($conn, $Dest);
$PossibleDestination = mysqli_fetch_all($DestRoute, MYSQLI_ASSOC);

//View 8
$TRange = 'SELECT Routes.RouteID, RunningTimes.StartTime, Routes.StartStation FROM Routes JOIN RunningTimes ON Routes.RouteID = RunningTimes.RouteID WHERE RunningTimes.StartTime BETWEEN "09:00:00" AND "13:00:00"';
$TimeRange = mysqli_query($conn, $TRange);
$RouteStartBetweenTimeRange = mysqli_fetch_all($TimeRange, MYSQLI_ASSOC); 

//View 9
$WeekdayRoute = 'SELECT * 
                FROM RunningTimes 
                WHERE NOT (Dayofweek = "Sunday" OR Dayofweek = "Saturday")'; 
$WdayRoute = mysqli_query($conn, $WeekdayRoute);
$WeekdayRoutes = mysqli_fetch_all($WdayRoute, MYSQLI_ASSOC);

//View 10
$Tprices = 'SELECT AgeType, Cost 
            FROM Ticket';
$Ticprices = mysqli_query($conn, $Tprices);
$TicketPrices = mysqli_fetch_all($Ticprices, MYSQLI_ASSOC);  

/*

$username = 'Rajiv'; // Replace this with the username you want to insert
$userPassword = 'Reddy'; // Replace this with the password
$location = 'Lomada'; // Replace this with the location

// Check if the username already exists
$checkQuery = "SELECT COUNT(*) as count FROM User WHERE Username = '$username'";
$result = $conn->query($checkQuery);

if ($result) {
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        echo "<script>alert('Username already exists. Please choose a different username.');</script>";
    } else {
        // Username doesn't exist, proceed with insertion
        $sql = "INSERT INTO User (Username, UserPassword, Location) VALUES ('$username', '$userPassword', '$location')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "<script>alert('Error with Username or Unfilled Cells.');</script>";
        }
    }
} else {
    echo "<script>alert('Error checking for existing username');</script>";


    
}

*/

// NOTE TO FUTURE KEVAUN -- THIS IS THE FORM STUFF YOU NEED TO CHANGE

/*

<form method="post" action="process_form.php">
    <input type="text" name="username" placeholder="Username"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="text" name="location" placeholder="Location"><br>
    <input type="submit" value="Submit">
</form>

*/


// close connection
mysqli_close($conn);

?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Railway System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="indexStylesheet.css">
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">RS</a>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About Us</a>
      </li>
    </ul>
    <a id="login-link" class="nav-link" href="#">Login/Signup</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>

  <!-- Main Content -->
  <div id="main-content">
    <!-- Tabs -->
    <ul class="nav nav-tabs flex-column" id="myTabs">
      <li class="nav-item">
        <a class="nav-link active" id="buy-tickets-tab" data-toggle="tab" href="#buy-tickets">Buy Tickets</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="routes-schedules-tab" data-toggle="tab" href="#routes-schedules">Routes & Schedules</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="trains-tab" data-toggle="tab" href="#trains">Trains</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="stations-tab" data-toggle="tab" href="#stations">Stations</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="edu-info-tab" data-toggle="tab" href="#edu-info">Educational Information</a>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
      <div class="tab-pane fade show active" id="buy-tickets">
        <h2>Buy Tickets Content</h2>

        <table>
    <thead>
        <tr>
            <th>Age Type</th>
            <th>Cost</th>
            <!-- Add more table headers if there are additional columns -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($TicketPrices as $TicketPrices): ?>
            <tr>
                <td><?php echo $TicketPrices['AgeType']; ?></td>
                <td><?php echo $TicketPrices['Cost']; ?></td>
                <!-- Display additional columns accordingly -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
      </div>

      <div class="tab-pane fade" id="routes-schedules">
        <h2>Routes & Schedules Content</h2>

        <table>
    <thead>
        <tr>
            <th>RunningTimeID</th>
            <th>RouteID</th>
            <th>StartTime</th>
            <th>EndTime</th>
            <th>Day of Week</th>
            <!-- Add more table headers if there are additional columns -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($WeekdayRoutes as $WeekdayRoutes): ?>
            <tr>
                <td><?php echo $WeekdayRoutes['RunningTimeID']; ?></td>
                <td><?php echo $WeekdayRoutes['RouteID']; ?></td>
                <td><?php echo $WeekdayRoutes['StartTime']; ?></td>
                <td><?php echo $WeekdayRoutes['EndTime']; ?></td>
                <td><?php echo $WeekdayRoutes['DayOfWeek']; ?></td>
                <!-- Display additional columns accordingly -->
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

      </div>
      <div class="tab-pane fade" id="trains">
        <h2>Trains Content</h2>
      </div>
      <div class="tab-pane fade" id="stations">
        <h2>Stations Content</h2>
      </div>
      <div class="tab-pane fade" id="edu-info">
        <h2>Educational Information Content</h2>
      </div>
    </div>
  </div>

  <!-- Script Links for Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>



</html>