




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
      </div>
      <div class="tab-pane fade" id="routes-schedules">
        <h2>Routes & Schedules Content</h2>
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