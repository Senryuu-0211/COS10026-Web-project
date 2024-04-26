<?php
session_start();
?>
<?php include('header.inc'); ?>

<body>
<?php include_once 'menu.inc'; ?>
	<h1>Confirmation</h1>
	<?php
    function sanitise_input($data)
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

		
		function date_validation($date)
		{
			$date_format = "d/m/Y";
		
			//check whether input date is in correct format
			$date_obj = DateTime::createFromFormat($date_format, $date);
			if ($date_obj === false) {
				// Invalid date format
				return false;
			}
		
			// Check if the date is valid
			$day = $date_obj->format('d');
			$month = $date_obj->format('m');
			$year = $date_obj->format('Y');
			if (!checkdate($month, $day, $year) || $date_obj->format($date_format) !== $date) {
				// Invalid date
				return false;
			}
		
			return true;
		}
		

		function age_validation($date)
		{
			$date_format = "d/m/Y";
			$min_age = 15;
			$max_age = 80;

			$date_obj = DateTime::createFromFormat($date_format, $date);

			if ($date_obj === false) {
				// Invalid date format
				return false;
			}

			// Calculate the age
			$today = new DateTime();

			if ($date_obj < $today) {
				$diff = $today->diff($date_obj);
				// Retrieve the number of years
				$age = $diff->y;

				return $age >= $min_age && $age <= $max_age;
			}		

			return false;
		}

		$postcode_validation = [
			"VIC" => "/^3/",
			"NSW" => "/^1|2/",
			"QLD" => "/^4|9/",
			"NT" => "/^08/",
			"WA" => "/^6/",
			"SA" => "/^5/",
			"TAS" => "/^7/",
			"ACT" => "/^0/"
		];
		
		function validate_postcode($postcode, $state)
		{
			return preg_match($GLOBALS["postcode_validation"][$state], $postcode);
		}
		
		function display_missing_error($field)
		{
			return sprintf("<p><strong>Missing field: %s </strong></p>", $field);
		}

		function display_invalid_error($field, $message)
		{
			return sprintf("<p><strong>Invalid field: %s </strong> %s</p>", $field, $message);
		}
		// Take data from apply.php
	try {
		if (isset($_POST["Finish-apply"])) {
		    $job_reference_number = sanitise_input($_POST["job"]);
		    $first_name = sanitise_input($_POST["name"]);
		    $last_name = sanitise_input($_POST["family"]);
		    $email = sanitise_input($_POST["email"]);
		    $phone_number = sanitise_input($_POST["number"]);
		    $date_of_birth = sanitise_input($_POST["date"]);
		    $gender = sanitise_input ($_POST["gender"]);
		    $street_address = sanitise_input($_POST["Street"]);
		    $suburb_town = sanitise_input($_POST["Town/Suburb"]);
		    $state = $_POST["state"];
		    $postcode = sanitise_input($_POST["postcode"]);
		    $skills = isset($_POST["skill"]) ? $_POST["skill"] : array();
		    $other_skill = isset($_POST["other"]) ? sanitise_input($_POST["other"]) : NULL;
		}
			else{
				throw new Exception("Have not receive form data");
			}

		//Validate
		if (empty($job_reference_number)) {
			throw new Exception("Job Reference Number is missing");
		}

		if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_reference_number)) {
			throw new Exception(display_invalid_error("Job Reference Number", "Invalid Job Reference Number"));
		}
		
		if (empty($first_name)) {
			throw new Exception("First Name is missing");
		}

		if (empty($last_name)) {
			throw new Exception("Last Name is missing");
		}

		if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name) || !preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
			throw new Exception(display_invalid_error("First Name and Last Name", "Your First Name and Last Name should only have <strong>letters</strong> and should not exceed <strong>20</strong> characters in length."));
		}

		if (empty($email)) {
			throw new Exception("Email is missing");
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception(display_invalid_error("Email", "Invalid email address"));
		}
		
		if (empty($phone_number)) {
			throw new Exception("Phone Number is missing");
		}

		if (!preg_match("/^[0-9\s]{8,12}$/", $phone_number)) {
			throw new Exception(display_invalid_error("Phone Number", "Invalid phone number"));
		}

		if (empty($date_of_birth)) {
			throw new Exception("Date of Birth is missing");
		}

		if (!date_validation($date_of_birth)) {
			throw new Exception(display_invalid_error("Date of Birth", "Wrong date format (dd/mm/yyyy) " . $date_of_birth));
		}
		
		if (empty($street_address)) {
			throw new Exception("Job Reference Number is missing");
		}

		if (!preg_match("/^.{1,40}$/", $street_address)) {
			throw new Exception(display_invalid_error("Street Address", "Only <strong>40</strong> words is acceptable in the street address."));
		}
		
		if (empty($suburb_town)) {
			throw new Exception("Town/Suburb is missing");
		}

		if (!preg_match("/^.{1,40}$/", $suburb_town)) {
			throw new Exception(display_invalid_error("Suburb/Town", "The suburb name you entered should not exceed <strong>40</strong> characters in length." . $suburb_town));
		}
		
		if (!age_validation($date_of_birth)) {
			throw new Exception(display_invalid_error("Date of Birth", "You have to be in the age group of 15 to 80 to apply the job"));
		}

		if (empty($postcode)) {
			throw new Exception("Postcode is missing");
		}
		
		if (!preg_match("/^[0-9]{4}$/", $postcode)) {
			throw new Exception(display_invalid_error("Postcode", "Only <strong>4</strong> digits is acceptable in Postcode."));
		} 
		
		if (empty($skills) && empty($other_skill)) {
			throw new Exception(display_missing_error("Skills"));
		}
		if (isset($_POST["skill"]) && in_array("others", $_POST["skill"]) && (empty($other_skill) || trim($other_skill) === "")) {
			throw new Exception(display_invalid_error("Skills", "Please provide the other skills you possessed"));
		}

		//Database
		require_once("./settings.php");

		$conn = @mysqli_connect(
			$host,
			$user,
			$pwd,
			$sql_db
		);

		if (!$conn) {
			die("Can not connect to the database: " . mysqli_connect_error());
		}		

		$mysqli_first_name = mysqli_real_escape_string($conn, $first_name);
		$mysqli_last_name = mysqli_real_escape_string($conn, $last_name);
		$mysqli_street_address = mysqli_real_escape_string($conn, $street_address);
		$mysqli_suburb_town = mysqli_real_escape_string($conn, $suburb_town);
		$mysqli_state = mysqli_real_escape_string($conn, $state);
		$mysqli_postcode = intval($postcode);
		$mysqli_email = mysqli_real_escape_string($conn, $email);
		$mysqli_phone_number = mysqli_real_escape_string($conn, $phone_number);
		$mysqli_gender = mysqli_real_escape_string($conn, $gender);
		$skill_python = in_array("python", $skills) ? "Yes" : "No";
		$skill_nlp = in_array("nlp", $skills) ? "Yes" : "No";
		$skill_pytorch = in_array("pytorch", $skills) ? "Yes" : "No";
		$skill_sql = in_array("sql", $skills) ? "Yes" : "No";
		$skill_datavisu = in_array("datavisu", $skills) ? "Yes" : "No";
		$other_skill = isset($_POST["other"]) ? trim(sanitise_input($_POST["other"])) : NULL;

			//Insert EOI records
		$insert_query = "INSERT INTO eoi (
			job_reference_number,
			first_name,
			last_name,
			street_address,
			suburb_town,
			states,
			gender,
			postcode,
			email_address,
			phone_number,
			skill_python,
			skill_nlp,
			skill_pytorch,
			skill_sql,
			skill_datavisu,
			Other_skills    
			) VALUES (
			'" . mysqli_real_escape_string($conn, $job_reference_number) . "',
			'$mysqli_first_name',
			'$mysqli_last_name',
			'$mysqli_street_address',
			'$mysqli_suburb_town',
			'$mysqli_state',
			'$mysqli_gender',
			'$mysqli_postcode',
			'$mysqli_email',
			'$mysqli_phone_number',
			'$skill_python',
			'$skill_nlp',
			'$skill_pytorch',
			'$skill_sql',
			'$skill_datavisu',
			" . (!empty($other_skill) ? "'" . mysqli_real_escape_string($conn, $other_skill) . "'" : "NULL") . "
		)";
			//Security
		$stmt = mysqli_prepare($conn, $insert_query);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["Error" => "Error preparing SQL statement"]);
        exit();
    }  
    $insert_result = mysqli_stmt_execute($stmt);
	
		if (!$insert_result) {
			die("<p>Error: Can not submit the form" . mysqli_error($conn) . "</p>");
		}


		// Confirmation message
		echo "<p><strong>EOI has been submitted!</strong>";
		echo "<p><strong>Here is your EOI ID " . mysqli_insert_id($conn) . ".</strong></p>";

		echo "<p><a href='apply.php'>Return Apply</a></p>";
		unset($_SESSION["form_data"]);
	} 
		catch (Exception $e) {
		echo "<p>Error: " . $e->getMessage() . "</p>";
	}

?>
<?php include('footer.inc'); ?>
</body>
</html>