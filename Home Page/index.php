<?php

session_start();
// Connect to the database
$conn = mysqli_connect('localhost:3306', 'root', '123456', 'RailwaySystemWebsite');

// Check connection
if (!$conn) {
  echo 'Connection error: ' . mysqli_connect_error();
}

//Views
{
  //View 1
  $RI = 'SELECT Routes.RouteID, Routes.Duration, Routes.Distance, Trains.TrainID, s1.Location AS StartStation, s2.Location AS EndStation FROM Routes JOIN Trains ON Routes.OperatingTrains = Trains.TrainID JOIN Stations s1 ON Routes.StartStation = s1.StationID JOIN Stations s2 ON Routes.EndStation = s2.StationID';
  $RI2 = mysqli_query($conn, $RI);
  $RouteInfo = mysqli_fetch_all($RI2, MYSQLI_ASSOC);


  //View 2
  $PassTrains = 'SELECT T.TrainID, MAX(R.Weight) AS MaximumWeight, SUM(R.PassengerCapacity) AS TotalPassengerCount
                  FROM Trains T 
                  JOIN RailCars R ON T.BodyRailCarType = R.ModelNumber 
                  JOIN Routes RT ON T.TrainID = RT.OperatingTrains 
                  WHERE R.Category = "Passenger" 
                  GROUP BY T.TrainID';

  $PasseTrain = mysqli_query($conn, $PassTrains);
  $PassengerTrains = mysqli_fetch_all($PasseTrain, MYSQLI_ASSOC);

  //View 3
  $RTC = 'SELECT r.RouteID,(SELECT t.TrainID FROM Trains t JOIN Routes rt ON rt.OperatingTrains = t.TrainID WHERE rt.RouteID = r.RouteID) AS OperatingTrainID FROM Routes r;';
  $RTC2 = mysqli_query($conn, $RTC);
  $RouteTrainCount = mysqli_fetch_all($RTC2, MYSQLI_ASSOC);

  //View 4
  $RCI = 'SELECT RailCars.ModelNumber, RailCars.ModelName FROM RailCars LEFT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType UNION SELECT RailCars.ModelNumber, RailCars.ModelName FROM RailCars RIGHT JOIN Trains ON RailCars.ModelNumber = Trains.EngineRailCarType';
  $RCI2 = mysqli_query($conn, $RCI);
  $RailCarInfo = mysqli_fetch_all($RCI2, MYSQLI_ASSOC);

  //View 5
  $RS = 'SELECT * FROM (SELECT "Weekday" AS DayCategory, r.RouteID, r.StartStation, r.EndStation, rt.StartTime, rt.EndTime, rt.DayOfWeek FROM Routes r INNER JOIN RunningTimes rt ON r.RouteID = rt.RouteID WHERE rt.DayOfWeek IN ("Monday", "Tuesday", "Wednesday", "Thursday", "Friday") UNION SELECT "Weekend" AS DayCategory, r.RouteID, r.StartStation, r.EndStation, rt.StartTime, rt.EndTime, rt.DayOfWeek FROM Routes r INNER JOIN RunningTimes rt ON r.RouteID = rt.RouteID Where rt.DayOfWeek IN ("Saturday", "Sunday")) AS CombinedData';
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


  //View 7.5


    $Dest2 = "SELECT 
    U.Location AS CurrentLocation, 
    S.Location AS PossibleDestination
FROM 
    User U 
JOIN 
    Routes R ON U.Location = (SELECT S.Location FROM Stations S WHERE S.StationID = R.StartStation) 
JOIN 
    Stations S ON R.EndStation = S.StationID
GROUP BY 
    U.Location, 
    S.Location";

    $DestRoute2 = mysqli_query($conn, $Dest2);
    $PossibleDestination2 = mysqli_fetch_all($DestRoute2, MYSQLI_ASSOC);


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
}

// ACCOUNT SIGNUP STUFF
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (isset($_POST['username'], $_POST['userPassword'])) {

    if ($_POST['username'] != "" && $_POST['userPassword'] != "") {

      $username = $_POST['username'];
      $userPassword = $_POST['userPassword'];
      $userLocation = $_POST['location'];

      // Check if the username already exists
      $checkQuery = "SELECT COUNT(*) as count FROM User WHERE Username = '$username'";
      $result = $conn->query($checkQuery);

      if ($result) {
        $row = $result->fetch_assoc();
        if ($row['count'] > 0) {
          echo "<script>alert('Username already exists. Please choose a different username.');</script>";
          echo "<script>window.location.href = '../Account Pages/signup.html';</script>";
          exit();

        } else {
          // Username doesn't exist, proceed with insertion
          $sql = "INSERT INTO User (Username, UserPassword,Location) VALUES ('$username', '$userPassword', '$userLocation')";

          if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Account Successfully Created. Please login');</script>";
            echo "<script>window.location.href = '../Account Pages/login.html';</script>";
            exit();
          } else {
            echo "<script>alert('Error with Username or Unfilled Cells.');</script>";
          }
        }
      } else {
        echo "<script>alert('Error checking for existing username');</script>";
      }
    }
  }
}


// ACCOUNT LOGIN STUFF
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['unlogin'], $_POST['uplogin'])) {
    $usernamelogin = $_POST['unlogin'];
    $userPasswordlogin = $_POST['uplogin'];

    // Check if the username exists and password matches
    $checkQuery = "SELECT UserPassword FROM User WHERE Username = '$usernamelogin'";
    $result2 = $conn->query($checkQuery);

    if ($result2 && $result2->num_rows > 0) {
      $row2 = $result2->fetch_assoc();
      $storedPassword = $row2['UserPassword'];

      // Check if the password matches
      if ($userPasswordlogin === $storedPassword) {
        echo "<script>alert('Successful Login');</script>";
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $usernamelogin;
        echo "<script>window.location.href = 'index.php';</script>";
      } else {
        echo "<script>alert('Credentials do not match');</script>";
        echo "<script>window.location.href = '../Account Pages/login.html';</script>";
      }
    } else {
      echo "<script>alert('Username does not exist');</script>";
      echo "<script>window.location.href = '../Account Pages/login.html';</script>";
    }
    exit();
  }
}


// MY TICKETS QUERY
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  $loggedInUsername = $_SESSION['username'];

  // Query to fetch tickets owned by the logged-in user
  $getUserTicketsQuery = "SELECT RouteID, TicketType, COUNT(*) as TicketCount FROM UserTickets WHERE Username = '$loggedInUsername' GROUP BY RouteID, TicketType";

  // Execute the query
  $userTicketsResult = mysqli_query($conn, $getUserTicketsQuery);

  if ($userTicketsResult) {
    $userTickets = mysqli_fetch_all($userTicketsResult, MYSQLI_ASSOC);
  }
}


// PURCHASE TICKET STUFF
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
  if (isset($_POST['Type'], $_POST['Route'])) {
    $ticketType = $_POST['Type'];
    $routeID = $_POST['Route'];
    $username = $_SESSION['username'];

    // Fetch the cost of the ticket based on the selected ticket type and route
    $ticketQuery = "SELECT Cost FROM Ticket WHERE AgeType = '$ticketType'";
    $ticketResult = mysqli_query($conn, $ticketQuery);

    if ($ticketResult && $ticketResult->num_rows > 0) {
      $ticket = mysqli_fetch_assoc($ticketResult);
      $cost = $ticket['Cost'];

      // Insert ticket details into UserTickets table
      $insertQuery = "INSERT INTO UserTickets (Username, RouteID, TicketType) VALUES ('$username', '$routeID', '$ticketType')";
      if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('Ticket Purchased');</script>";
        echo "<script>window.location.href = 'index.php';</script>";
        exit();
      } else {
        echo "<script>alert('Error purchasing ticket.');</script>";
      }
    } else {
      echo "<script>alert('Ticket not found.');</script>";
    }
  }
}


// DELETE TICKET STUFF
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['removeTicket'])) {
  // Retrieve the RouteID and TicketType from the form
  $routeID = $_POST['routeID'];
  $ticketType = $_POST['ticketType'];

  // Get the logged-in user's username
  $loggedInUsername = $_SESSION['username'];

  // Query to remove one specified ticket for the logged-in user
  $removeTicketQuery = "DELETE FROM UserTickets 
                        WHERE Username = '$loggedInUsername' 
                        AND RouteID = '$routeID' 
                        AND TicketType = '$ticketType' 
                        LIMIT 1"; // Limit the deletion to one row

  // Execute the query to remove the ticket
  if (mysqli_query($conn, $removeTicketQuery)) {
    // Redirect back to the page with refreshed ticket data
    echo "<script>alert('Ticket Cancelled');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
  } else {
    echo "<script>alert('Error removing ticket');</script>";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Railway System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="index-stylesheet.css">
</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand"><img id="logo" src="..\Images\train-icon.png"></a>
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../About Page/about.html">About Us</a>
      </li>
    </ul>
    <?php

    // Check if the user is logged in (based on the session variable)
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
      // Show logout button if logged in
      echo '<form action="logout.php" method="post"><button type="submit" class="btn btn-dark login-signup-btn nav-link">Logout</button></form>';
    } else {
      // Show the login/signup link if not logged in
      echo '<a id="login-link" class="nav-link btn btn-dark login-signup-btn" href="..\Account Pages\signup.html">Login/Signup</a>';
    }
    ?>
    </button>
  </nav>


  <div id="main-content">
    <!-- Tabs List -->
    <ul class="nav nav-tabs flex-column" id="myTabs">
      <?php
      // Check if the user is logged in (based on the session variable)
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        // Show "My Tickets" tab if logged in
        echo '<li class="nav-item"><a class="nav-link" href="#my-tickets" data-toggle="tab">My Tickets</a></li>';
      }
      ?>
      <li class="tab-item">
        <a class="nav-link active" id="buy-tickets-tab" data-toggle="tab" href="#buy-tickets">Buy Tickets</a>
      </li>
      <li class="tab-item">
        <a class="nav-link" id="routes-schedules-tab" data-toggle="tab" href="#routes-schedules">Routes & Schedules</a>
      </li>
      <li class="tab-item">
        <a class="nav-link" id="trains-tab" data-toggle="tab" href="#trains">Trains</a>
      </li>
      <li class="tab-item">
        <a class="nav-link" id="stations-tab" data-toggle="tab" href="#stations">Stations</a>
      </li>
      <li class="tab-item">
        <a class="nav-link" id="edu-info-tab" data-toggle="tab" href="#edu-info">Educational Information</a>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
      <?php
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        echo '<div class="tab-pane fade" id="my-tickets">';
        echo '<h2>My Tickets</h2>';

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Route No.</th>';
        echo '<th>Type</th>';
        echo '<th>Count</th>';
        echo "<th>Cancel</th>";
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($userTickets as $ticket) {
          echo '<tr>';
          echo '<form method="post" action="index.php">'; // Assuming remove_ticket.php is the script handling deletions
          echo '<td>' . $ticket["RouteID"] . '</td>';
          echo '<td>' . $ticket["TicketType"] . '</td>';
          echo '<td>' . $ticket["TicketCount"] . '</td>';
          echo '<td>' . '<input type="hidden" name="routeID" value="' . $ticket["RouteID"] . '">' .
            '<input type="hidden" name="ticketType" value="' . $ticket["TicketType"] . '">' .
            '<button type="submit" name="removeTicket" class="btn btn-danger">Cancel</button>' . '</td>';
          echo '</form>';
          echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

        echo '</tbody>';
        echo '</table>';

        echo '</div>';
      }
      ?>

      <!-- Buy Tickets Tab -->
      <div class="tab-pane fade show active" id="buy-tickets">
        <h2>Buy Tickets</h2>
        <img src="../Images/train-tickets.jpg" class="page-image">
        <table>
          <tr>
            <th>Type</th>
            <th>Price ($)</th>
          </tr>
          <?php foreach ($TicketPrices as $TicketPrice): ?>
            <tr>
              <td>
                <?php echo $TicketPrice['AgeType']; ?>
              </td>
              <td>
                <?php echo $TicketPrice['Cost']; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
        <br>
        <?php
        // Check if the user is logged in (based on the session variable)
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          //Do nothing
        } else {
          // Show the prompt to login/signup link if not logged in
          echo '<p id="login-signup-msg">To purchase tickets, <a href="..\Account Pages\signup.html">Create an Account</a> or <a
          href="..\Account Pages\login.html">Log in.</a></p>';
        }
        ?>

        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
          // Show logout button if logged in
        
          echo
            '
          <form class="route-selection-form" method="post">
          <i>Select your route</i>:<br>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2001" > Route 2001
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2002"> Route 2002
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2003" required> Route 2003
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2004"> Route 2004
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2005"> Route 2005
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Route" value="2006"> Route 2006
            </label>
          </div>
          <br><br>
          <i>Select ticket type</i>:<br>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-outline-success">
              <input type="radio" name="Type" value="Adult" > Adult
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Type" value="Child" required> Child
            </label>
            <label class="btn btn-outline-success">
              <input type="radio" name="Type" value="Senior"> Senior
            </label>
          </div>
          <br><br>
          <div class="ticket-buttons mt-3">
            <button type="submit" name="purchase" class="btn btn-success" style = "margin-bottom: 20px;">Purchase Ticket</button>
          </div>
          </form>
        ';

        } else {
          // Show the login/signup link if not logged in
        }
        ?>
      </div>


      <!-- Routes and Schedules Tab -->
      <div class="tab-pane fade" id="routes-schedules">
        <h2>Routes & Schedules</h2>
        <img src="../Images/train-tracks.jpg" class="page-image">
        <h4>Route Details</h4>
        <table>
          <thead>
            <tr>
              <th>Route No.</th>
              <th>Duration</th>
              <th>Distance (km)</th>
              <th>Train No.</th>
              <th>From</th>
              <th>To</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($RouteInfo as $RI): ?>
              <tr>
                <td>
                  <?php echo $RI['RouteID']; ?>
                </td>
                <td>
                  <?php echo $RI['Duration']; ?>
                </td>
                <td>
                  <?php echo $RI['Distance']; ?>
                </td>
                <td>
                  <?php echo $RI['TrainID']; ?>
                </td>
                <td>
                  <?php echo $RI['StartStation']; ?>
                </td>
                <td>
                  <?php echo $RI['EndStation']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <h4>Longest Routes </h4>
        <table>
          <thead>
            <tr>
              <th>Route No.</th>
              <th>Distance (km)</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($LongestRoutes as $Long): ?>
              <tr>
                <td>
                  <?php echo $Long['RouteID']; ?>
                </td>
                <td>
                  <?php echo $Long['Distance']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <h4>Schedule</h4>
        <table>
          <thead>
            <tr>
              <th>Running Time No.</th>
              <th>Route No.</th>
              <th>Departure Time</th>
              <th>Arrival Time</th>
              <th>Day</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($WeekdayRoutes as $WeekdayRoute): ?>
              <tr>
                <td>
                  <?php echo $WeekdayRoute['RunningTimeID']; ?>
                </td>
                <td>
                  <?php echo $WeekdayRoute['RouteID']; ?>
                </td>
                <td>
                  <?php echo $WeekdayRoute['StartTime']; ?>
                </td>
                <td>
                  <?php echo $WeekdayRoute['EndTime']; ?>
                </td>
                <td>
                  <?php echo $WeekdayRoute['DayOfWeek']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br><br>
      </div>

      <!--Trains Tab -->
      <div class="tab-pane fade" id="trains">
        <h2>Trains</h2>
        <h4>Our Trains</h4>
        <div id="educational-content">
        </div>
        <br>
        <h4>Train Details</h4>
        <table>
          <thead>
            <tr>
              <th>Train No.</th>
              <th>Maximum Load (kgs)</th>
              <th>Passenger Capacity</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($PassengerTrains as $PassTrains): ?>
              <tr>
                <td>
                  <?php echo $PassTrains['TrainID']; ?>
                </td>
                <td>
                  <?php echo $PassTrains['MaximumWeight']; ?>
                </td>
                <td>
                  <?php echo $PassTrains['TotalPassengerCount']; ?>
                </td>

              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <h4>Engine & Car Models</h4>
        <table>
          <thead>
            <tr>
              <th>Model No.</th>
              <th>Model Name</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($RailCarInfo as $RCI): ?>
              <tr>
                <td>
                  <?php echo $RCI['ModelNumber']; ?>
                </td>
                <td>
                  <?php echo $RCI['ModelName']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <h4>Route Operating Train</h4>
        <table>
          <thead>
            <tr>
              <th>Route No.</th>
              <th>Train No.</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($RouteTrainCount as $RTC): ?>
              <tr>
                <td>
                  <?php echo $RTC['RouteID']; ?>
                </td>
                <td>
                  <?php echo $RTC['OperatingTrainID']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br><br>
      </div>

      <!-- Stations Tab -->
      <div class="tab-pane fade" id="stations">
        <h2>Stations Content</h2>
        <img src="../Images/train-station.jpg" class="page-image">
        <div id="station-info">
          <h4>My Stations</h4>
          <table>
            <?php
            // Check if the user is logged in (based on the session variable)
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
              foreach ($PossibleDestination as $PD) {
                echo '
                  <thead>
                    <tr>
                      <th>Closest Station</th>
                      <th>Possible Destinations</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td>' . $PD["CurrentLocation"] . '</td>
                    <td>' . $PD["PossibleDestination"] . '</td>
                    </tr>
                  </tbody>';
              }
            } else {
              // Show the prompt to login/signup link if not logged in
              echo '<p id="login-signup-msg">To view nearby stations, <a href="..\Account Pages\signup.html">Create an Account</a> or <a
              href="..\Account Pages\login.html">Log in.</a></p>';
            }
            ?>
          </table>
          <br><br>
          <h4>All Stations</h4>
          <table>
          <thead>
                    <tr>
                      <th>Origin</th>
                      <th>Destination</th>
                    </tr>
                  </thead>
            <?php
            // Check if the user is logged in (based on the session variable)
            

              foreach ($PossibleDestination2 as $PD2) {
                echo '
                  
                  <tbody>
                    <tr>
                    <td>' . $PD2["CurrentLocation"] . '</td>
                    <td>' . $PD2["PossibleDestination"] . '</td>
                    </tr>
                  </tbody>';
              }
            ?>
          </table>

        </div>
      </div>

      <!-- Educational Information Tab -->
      <div class="tab-pane fade" id="edu-info">
        <h2>Educational Information</h2>

        <div id="educational-info">
          <?php
          // Include the PHP file that generates the XML content
          include 'display-educational-info.php';
          ?>
        </div>

        <!-- SOAP stuff -->
        <script>
          fetch('http://localhost/RailwaySystemWebsite/Home%20Page/rest.php')
            .then(response => {
              if (!response.ok) {
                throw new Error('Network response was not ok');
              }
              return response.json();
            })
            .then(data => {
              let html = '<p>';

              if (Array.isArray(data)) {
                data.forEach(info => {
                  html += `<p><b>${info.Header}</b><br>${info.information}</p><img style="height: 20vw; width=50vw; border: 3px solid black;" src="${info.images}"><br><br>`;
                });
                html += '</p>';

                // Display fetched data in the designated div
                document.getElementById('educational-content').innerHTML = html;
              } else {
                console.error('Received data is not an array:', data);
              }
            })

            .catch(error => {
              console.error('Error:', error);
              alert('Error fetching data');
            });
        </script>

      </div>
    </div>
  </div>

  <!-- Script Links for Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>



</html>