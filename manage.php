<?php
session_start();
if (!isset($_SESSION["uid"])) {
   header("Location: login.php");
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    }
    $_SESSION['LAST_ACTIVITY'] = time();
?>

<style>
        table {
            border-collapse: collapse;
            height: 360px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }

        .manage-title{
           display: flex;
           align-items: center;
           justify-content: center;
           font-size: 2rem;
           font-weight: bold;
        }

        /* Responsive Styling */
        @media screen and (max-width: 600px) {
            table {
                border: 0;
            }
            table thead {
                display: none;
            }
            table tr {
                margin-bottom: 10px;
                display: block;
                border: 1px solid #ccc;
            }
            table td {
                display: grid;
                text-align: left;
                border: none;
            }
            table td:before {
                content: attr(data-title);
                float: left;
                font-weight: bold;
            }
        }
</style>
  
<?php include('header.inc'); ?>
<body>
<?php include_once 'menu.inc'; ?>    

  <div class="applymanage">
    <h2>Human Resources Manager Queries</h2>
    <!-- Query 1: List all EOIs -->
    <h4>List all EOIs</h4>
    <form action="manage.php" method="post" class="form-wrapper">
      <input type="hidden" name="action" value="listAllEOIs">
      <input type="submit" value="List All EOIs" id="searchsubmit">
    </form>

    <h4>List EOIs by Job Reference Number</h4>
    <form action="manage.php" method="post" class="form-wrapper">
      <input type="hidden" name="action" value="listEOIsByJobRef">
      <label for="job_reference_number">Job Reference Number:</label>
      <input type="text" id="search" name="job_reference_number" placeholder="Search with job reference number" required> 
      <input type="submit" value="Search" id="searchsubmit">
    </form>

    <h4>List EOIs by Applicant Name</h4>
    <form action="manage.php" method="post" class="form-wrapper">
      <input type="hidden" name="action" value="listEOIsByApplicantName">
      
      <input type="text" id="search" placeholder="Enter First Name" name="first_name">
      
      <input type="text" id="search" placeholder="Enter Last Name" name="last_name">
      <input type="submit" value="List" id="searchsubmit">
    </form>

    <h4>Delete EOIs by Job Reference Number</h4>
    <form action="manage.php" method="post" class="form-wrapper">
      <input type="hidden" name="action" value="deleteEOIsByJobRef">
      <label for="job_reference_delete">Job Reference Number:</label>
      <input type="text" id="search" name="job_reference_number">
      <input type="submit" value="Delete" id="searchsubmit">
    </form>

    <h4>Change EOI Status</h4>
    <form action="manage.php" method="post" class="form-wrapper">
      <input type="hidden" name="action" value="changeEOIStatus">
      
      <input type="text" id="search" name="eoi_id" placeholder="Enter EOI Number">
      <label for="new_status">New Status: </label>

      <select name="new_status" id="new_status">
        <option value="New" selected="selected">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
      </select>

      <input type="submit" value="Change Status" id="searchsubmit">
    </form>
  </div>

  <div class="table-responsive">
        <table class="table bg-white">
            <thead class="bg-dark text-light">
                <tr>
                    <th>EOI Number</th>
                    <th>Job Reference Number</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Street Address</th>
                    <th>Suburb/Town</th>
                    <th>State</th>
                    <th>Gender</th>
                    <th>Postcode</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Python Skill</th>
                    <th>NLP Skill</th>
                    <th>Pytorch Skill</th>
                    <th>SQL Skill</th>
                    <th>Datavisu Skill</th>
                    <th>Other Skill Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

  <?php
  function generateSortFieldDropdown()
  {
    $columns = [
      'EOInumber' => 'EOI Number',
      'job_reference_number' => 'Job Reference Number',
      'first_name' => 'First Name',
      'last_name' => 'Last Name',
      'street_address' => 'Street Address',
      'suburb_town' => 'Suburb/Town',
      'states' => 'State',
      'gender' => 'Gender',
      'postcode' => 'Postcode',
      'email_address' => 'Email Address',
      'phone_number' => 'Phone Number',
      'skill_python' => 'Python Skill',
      'skill_nlp' => 'NLP Skill',
      'skill_pytorch' => 'Pytorch Skill',
      'skill_sql' => 'SQL Skill',
      'skill_datavisu' => 'Datavisu Skill',
      'other_skill' => 'Other Skill Description',
      'status' => 'Status'
    ];

    echo '<label for="sort_field">Sort By:</label>';
    echo '<select name="sort_field" id="sort_field">';
    foreach ($columns as $key => $value) {
      $selected = ($key == 'EOInumber') ? 'selected' : '';
      echo "<option value=\"$key\" $selected>$value</option>";
    }
    echo '</select>';
  }

  require_once("./settings.php");

  $conn = @mysqli_connect(
      $host,
      $user,
      $pwd,
      $sql_db
  );

  $table = 'eoi';

  if (!$conn) {
      echo "<p>Database connection failure </p>";
  } else {

      function sanitise_input($data)
      {
          $data = trim($data);
          $data = stripcslashes($data);
          $data = htmlspecialchars($data);
          return $data;
      }

      // Function to display EOIs in a table
      function displayEOIs($result)
      {
          if (mysqli_num_rows($result) > 0) {

              while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td data-title='EOI Number'>{$row['EOInumber']}</td>";
                  echo "<td data-title='Job Reference Number'>{$row['job_reference_number']}</td>";
                  echo "<td data-title='First Name'>{$row['first_name']}</td>";
                  echo "<td data-title='Last Name'>{$row['last_name']}</td>";
                  echo "<td data-title='Street Address'>{$row['street_address']}</td>";
                  echo "<td data-title='Suburb/Town'>{$row['suburb_town']}</td>";
                  echo "<td data-title='State'>{$row['states']}</td>";
                  echo "<td data-title='Gender'>{$row['gender']}</td>";
                  echo "<td data-title='Postcode'>{$row['postcode']}</td>";
                  echo "<td data-title='Email Address'>{$row['email_address']}</td>";
                  echo "<td data-title='Phone Number'>{$row['phone_number']}</td>";
                  echo "<td data-title='Python SKill'>{$row['skill_python']}</td>";
                  echo "<td data-title='NLP SKill'>{$row['skill_nlp']}</td>";

                  echo "<td data-title='Pytorch SKill'>{$row['skill_pytorch']}</td>";
                  echo "<td data-title='SQL SKill'>{$row['skill_sql']}</td>";

                  echo "<td data-title='Datavisu SKill'>{$row['skill_datavisu']}</td>";
                  echo "<td data-title='Other SKill Description'>{$row['Other_skills']}</td>";
                  echo "<td data-title='Status SKill'>{$row['Status']}</td>";
                  echo "</tr>";
                  echo "</tbody>";
              }

          } else {
              echo "No EOIs found.";
          }
      }

      function listAndSortEOIs($conn, $query, $sortField, $title)
      {
          $query .= " ORDER BY $sortField";
          $result = mysqli_query($conn, $query);

          echo "<h2>$title (Sorted by $sortField)</h2>";
          displayEOIs($result);
      }

      $sortField = isset($_POST['sort_field']) ? sanitise_input($_POST['sort_field']) : 'EOInumber';

      // Check if a specific action was submitted
      if (isset($_POST['action'])) {
          $action = sanitise_input($_POST['action']);

          if ($action === 'listAllEOIs') {
              $query = "SELECT * FROM $table";
              $result = mysqli_query($conn, $query);
              listAndSortEOIs($conn, $query, $sortField, "List of All EOIs");

          } elseif ($action === 'listEOIsByJobRef') {
            $job_reference_number = sanitise_input($_POST['job_reference_number']);
              $query = "SELECT * FROM $table WHERE job_reference_number = '$job_reference_number'";
              $result = mysqli_query($conn, $query);
              listAndSortEOIs($conn, $query, $sortField, "<h2>EOIs for Job Reference Number: $job_reference_number</h2>");

          } elseif ($action === 'listEOIsByApplicantName') {
              $firstName = sanitise_input($_POST['first_name']);
              $lastName = sanitise_input($_POST['last_name']);
              $query = "SELECT * FROM $table WHERE first_name = '$firstName' OR last_name = '$lastName'";
              $result = mysqli_query($conn, $query);
              listAndSortEOIs($conn, $query, $sortField, "<h2>EOIs for Applicant: $firstName $lastName</h2>");
            
            } elseif ($action === 'deleteEOIsByJobRef') {
            $job_reference_number = sanitise_input($_POST['job_reference_number']);
                $deleteQuery = "DELETE FROM $table WHERE job_reference_number = '$job_reference_number'";
                $deleteResult = mysqli_query($conn, $deleteQuery);

                if ($deleteResult) {
                    echo "EOIs with job reference number $job_reference_number deleted successfully.";
                } else {
                    echo "Error deleting EOIs with job reference number $job_reference_number.";
                }

          } elseif ($action === 'changeEOIStatus') {
              // Get the EOI ID and new status from the form input
              $eoiID = sanitise_input($_POST['eoi_id']);
              $newStatus = sanitise_input($_POST['new_status']);

              // Query to change the status of an EOI
              $updateQuery = "UPDATE $table SET status = '$newStatus' WHERE EOInumber = $eoiID";
              $updateResult = mysqli_query($conn, $updateQuery);

              if ($updateResult) {
                  echo "EOI status updated successfully.";
              } else {
                  echo "Error updating EOI status.";
              }
          }
      }
      mysqli_close($conn);
  }
  ?>
  </table>
  </div>
  <a href="#" class="back-to-top">
      <span class="material-icons"><i class="fa-solid fa-arrow-up"></i></span>
  </a>
  <div><a href="logout.php" class="logout">Logout</a></div>

  <?php include('footer.inc'); ?>
</body>

</html>