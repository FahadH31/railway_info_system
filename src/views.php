<?php

//View 1
$RI = 'SELECT routes.RouteID, routes.Duration, routes.Distance, trains.TrainID, s1.Location 
AS StartStation, s2.Location AS EndStation 
FROM routes JOIN trains ON routes.OperatingTrains = trains.TrainID 
JOIN stations s1 ON routes.StartStation = s1.StationID JOIN stations s2 ON routes.EndStation = s2.StationID';
$RI2 = mysqli_query($conn, $RI);
$RouteInfo = mysqli_fetch_all($RI2, MYSQLI_ASSOC);


//View 2
$TD = 'SELECT t.TrainID, MAX(r.Weight) AS MaximumWeight, SUM(r.PassengerCapacity) AS TotalPassengerCount
  FROM trains t 
  JOIN railcars r ON t.BodyRailCarType = r.ModelNumber 
  JOIN routes rt ON t.TrainID = rt.OperatingTrains 
  WHERE r.Category = "Passenger" 
  GROUP BY t.TrainID';

$TD2 = mysqli_query($conn, $TD);
$TrainDetails = mysqli_fetch_all($TD2, MYSQLI_ASSOC);


//View 3
$ROT = 'SELECT r.RouteID,
(SELECT t.TrainID 
FROM trains t JOIN routes rt 
ON rt.OperatingTrains = t.TrainID WHERE rt.RouteID = r.RouteID) 
AS OperatingTrainID FROM routes r;';
$ROT2 = mysqli_query($conn, $ROT);
$RouteOperatingTrain = mysqli_fetch_all($ROT2, MYSQLI_ASSOC);


//View 4
$RCI = 'SELECT railcars.ModelNumber, railcars.ModelName 
FROM railcars LEFT JOIN trains ON railcars.ModelNumber = trains.EngineRailCarType 
UNION SELECT railcars.ModelNumber, railcars.ModelName FROM railcars 
RIGHT JOIN trains ON railcars.ModelNumber = trains.EngineRailCarType';
$RCI2 = mysqli_query($conn, $RCI);
$RailCarInfo = mysqli_fetch_all($RCI2, MYSQLI_ASSOC);


//View 5
$RS = 'SELECT * 
                FROM runningtimes';
$RS2 = mysqli_query($conn, $RS);
$RouteSchedule = mysqli_fetch_all($RS2, MYSQLI_ASSOC);


//View 6
$Long = 'SELECT RouteID, Distance
    FROM routes
    ORDER BY Distance DESC';
$LongR = mysqli_query($conn, $Long);
$LongestRoutes = mysqli_fetch_all($LongR, MYSQLI_ASSOC);


//View 7
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $loggedInUsername = $_SESSION['username'];
  $Dest = "SELECT DISTINCT u.Username, u.Location AS CurrentLocation, s.Location AS PossibleDestination 
  FROM user u 
  JOIN routes r ON u.Location = (SELECT s.Location FROM stations s WHERE s.StationID = r.StartStation) 
  JOIN stations s ON r.EndStation = s.StationID
  WHERE u.Username = '$loggedInUsername'";
  $DestRoute = mysqli_query($conn, $Dest);
  $PossibleDestination = mysqli_fetch_all($DestRoute, MYSQLI_ASSOC);

}


//View 8
$Dest2 = "SELECT 
    u.Location AS CurrentLocation, 
    s.Location AS PossibleDestination
    FROM user u JOIN routes r ON u.Location = (SELECT s.Location FROM stations s WHERE s.StationID = r.StartStation) JOIN 
    stations s ON r.EndStation = s.StationID GROUP BY u.Location, s.Location";

$DestRoute2 = mysqli_query($conn, $Dest2);
$PossibleDestination2 = mysqli_fetch_all($DestRoute2, MYSQLI_ASSOC);


//View 9
$Tprices = 'SELECT AgeType, Cost 
  FROM ticket';
$Ticprices = mysqli_query($conn, $Tprices);
$TicketPrices = mysqli_fetch_all($Ticprices, MYSQLI_ASSOC);
?>