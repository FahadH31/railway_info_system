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
  <?php
    // Include required PHP files
    require_once('db-connection.php');
    require_once('views.php');
    require_once('account-operations.php');
  ?>

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
            <?php foreach ($RouteSchedule as $RS): ?>
              <tr>
                <td>
                  <?php echo $RS['RunningTimeID']; ?>
                </td>
                <td>
                  <?php echo $RS['RouteID']; ?>
                </td>
                <td>
                  <?php echo $RS['StartTime']; ?>
                </td>
                <td>
                  <?php echo $RS['EndTime']; ?>
                </td>
                <td>
                  <?php echo $RS['DayOfWeek']; ?>
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
              <th>Weight (tons)</th>
              <th>Passenger Capacity</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($TrainDetails as $TD): ?>
              <tr>
                <td>
                  <?php echo $TD['TrainID']; ?>
                </td>
                <td>
                  <?php echo $TD['MaximumWeight']; ?>
                </td>
                <td>
                  <?php echo $TD['TotalPassengerCount']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br>
        <h4>Engine & RailCar Models</h4>
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
            <?php foreach ($RouteOperatingTrain as $ROT): ?>
              <tr>
                <td>
                  <?php echo $ROT['RouteID']; ?>
                </td>
                <td>
                  <?php echo $ROT['OperatingTrainID']; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <br><br>
      </div>

      <!-- Stations Tab -->
      <div class="tab-pane fade" id="stations">
        <h2>Stations</h2>
        <img src="../Images/train-station.jpg" class="page-image">
        <div id="station-info">
          <h4>My Stations</h4>
          <table>
            <?php

            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo '
            <thead>
              <tr>
                <th>Closest Station</th>
                <th>Possible Destinations</th>
              </tr>
            </thead>';

            }
            // Check if the user is logged in (based on the session variable)
            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
              foreach ($PossibleDestination as $PD) {
              echo '    
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
          <br>
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
          <br><br>
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

        <!-- REST stuff -->
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