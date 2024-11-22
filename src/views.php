<?php

//View 1
$RI = 'SELECT Routes.RouteID, Routes.Duration, Routes.Distance, Trains.TrainID, s1.Location 
AS StartStation, s2.Location AS EndStation 
FROM Routes JOIN Trains ON Routes.OperatingTrains = Trains.TrainID 
JOIN Stations s1 ON Routes.StartStation = s1.StationID JOIN Stations s2 ON Routes.EndStation = s2.StationID';
$RI2 = mysqli_query($conn, $RI);
$RouteInfo = mysqli_fetch_all($RI2, MYSQLI_ASSOC);


//View 2
$TD = 'SELECT T.TrainID, MAX(R.Weight) AS MaximumWeight, SUM(R.PassengerCapacity) AS TotalPassengerCount
  FROM Trains T 
  JOIN RailCars R ON T.BodyRailCarType = R.ModelNumber 
  JOIN Routes RT ON T.TrainID = RT.OperatingTrains 
  WHERE R.Category = "Passenger" 
  GROUP BY T.TrainID';

$TD2 = mysqli_query($conn, $TD);
$TrainDetails = mysqli_fetch_all($TD2, MYSQLI_ASSOC);



//View 3
$ROT = 'SELECT r.RouteID,
(SELECT t.TrainID 
FROM Trains t JOIN Routes rt 
ON rt.OperatingTrains = t.TrainID WHERE rt.RouteID = r.RouteID) 
AS OperatingTrainID FROM Routes r;';
$ROT2 = mysqli_query($conn, $ROT);
$RouteOperatingTrain = mysqli_fetch_all($ROT2, MYSQLI_ASSOC);


//View 4
$RCI = 'SELECT RailCars.ModelNumber, RailCars.ModelName 
FROM RailCars LEFT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType 
UNION SELECT RailCars.ModelNumber, RailCars.ModelName FROM RailCars 
RIGHT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType';
$RCI2 = mysqli_query($conn, $RCI);
$RailCarInfo = mysqli_fetch_all($RCI2, MYSQLI_ASSOC);



//View 5
$RS = 'SELECT * 
                FROM RunningTimes';
$RS2 = mysqli_query($conn, $RS);
$RouteSchedule = mysqli_fetch_all($RS2, MYSQLI_ASSOC);



//View 6
$Long = 'SELECT RouteID, Distance
    FROM Routes
    ORDER BY Distance DESC';
$LongR = mysqli_query($conn, $Long);
$LongestRoutes = mysqli_fetch_all($LongR, MYSQLI_ASSOC);



//View 7
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $loggedInUsername = $_SESSION['username'];
  $Dest = "SELECT DISTINCT U.Username, U.Location AS CurrentLocation, S.Location AS PossibleDestination 
  FROM User U 
  JOIN Routes R ON U.Location = (SELECT S.Location FROM Stations S WHERE S.StationID = R.StartStation) 
  JOIN Stations S ON R.EndStation = S.StationID
  WHERE U.Username = '$loggedInUsername'";
  $DestRoute = mysqli_query($conn, $Dest);
  $PossibleDestination = mysqli_fetch_all($DestRoute, MYSQLI_ASSOC);

}



//View 8
$Dest2 = "SELECT 
    U.Location AS CurrentLocation, 
    S.Location AS PossibleDestination
    FROM User U JOIN Routes R ON U.Location = (SELECT S.Location FROM Stations S WHERE S.StationID = R.StartStation) JOIN 
    Stations S ON R.EndStation = S.StationID GROUP BY U.Location, S.Location";

$DestRoute2 = mysqli_query($conn, $Dest2);
$PossibleDestination2 = mysqli_fetch_all($DestRoute2, MYSQLI_ASSOC);



//View 9
$Tprices = 'SELECT AgeType, Cost 
  FROM Ticket';
$Ticprices = mysqli_query($conn, $Tprices);
$TicketPrices = mysqli_fetch_all($Ticprices, MYSQLI_ASSOC);
?>