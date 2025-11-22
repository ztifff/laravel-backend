<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance</title>
</head>
<body>
    <h1>Student Attendance</h1>

    <form action="" method="post">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="status">Attendance:</label>
        <select id="status" name="status" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select><br><br>

        <button type="submit">Submit</button>
    </form>

    <button><a href="Attendance.php">Back to Attendance Management</a></button>

    <?php 
    $conn = mysqli_connect('db','root','rootpassword','studentAttendance');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $student_id = $conn->real_escape_string($_POST['student_id']);
        $date = $conn->real_escape_string($_POST['date']);
        $status = $conn->real_escape_string($_POST['status']);

        $sql = "INSERT INTO attendance (student_id, date, status) 
                VALUES ('$student_id', '$date', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>New student added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
        }
    }
    ?>

    
</body>
</html>