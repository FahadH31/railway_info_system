<?php
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
          echo "<script>window.location.href = 'signup.html';</script>";
          exit();

        } else {
          // Username doesn't exist, proceed with insertion
          $sql = "INSERT INTO User (Username, UserPassword,Location) VALUES ('$username', '$userPassword', '$userLocation')";

          if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Account Successfully Created. Please login');</script>";
            echo "<script>window.location.href = 'login.html';</script>";
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
        echo "<script>window.location.href = 'login.html';</script>";
      }
    } else {
      echo "<script>alert('Username does not exist');</script>";
      echo "<script>window.location.href = 'login.html';</script>";
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