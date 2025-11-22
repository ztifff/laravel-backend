<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
</head>
<style>

</style>
<body>
    <h1>Student Attendance Management System</h1>
    <button><a href="Student.php">Add Student</a></button>

    <!-- Search Student -->
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search by name or ID" required
               value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>">
        <button type="submit">Search</button>
        <button><a href="Attendance.php">Reset</a></button>
    </form>

    <?php 
    $conn = mysqli_connect('db','root','rootpassword','studentAttendance');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $conn->real_escape_string($_POST['search']);
        $sql = "SELECT * FROM attendance WHERE student_id LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM attendance";
    }

    // Handle deletion
    if (isset($_GET['id'])) {
        $delete_id = intval($_GET['id']);
        $conn->query("DELETE FROM attendance WHERE id=$delete_id");

        $conn->query("SET @count = 0");
        $conn->query("UPDATE attendance SET id = @count := @count + 1 ORDER BY id");
        $conn->query("ALTER TABLE attendance AUTO_INCREMENT = 1");
        
        echo "<p>Student record deleted successfully.</p>";
    }

    $result = $conn->query($sql);
    echo "<h2>Student List</h2>";
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>
    <tr>
        <th>ID</th>
        <th>Student ID</th>
        <th>Attendance</th>
        <th>Action</th>
    </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>" . ($row["id"]) . "</td>
         <td>" . ($row["student_id"]) . "</td>
         <td>" . ($row["status"]) . "</td>
         <td>
             <a href='Student.php?id=" . $row["id"] . "'>Edit</a> | 
             <a href='Attendance.php?id=" . $row["id"] . "' onclick=\"return confirm('Are you sure?')\">Delete</a>
         </td>
     </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No students found.</p>";
    }


    ?>

</body>
</html>