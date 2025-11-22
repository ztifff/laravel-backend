<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Activity 3</title>
</head>
<body>
    <h2>Add Task</h2>
    <form action="" method="post" novalidate>
        <div>
          <label for="task">Task</label>
          <input id="task" name="task" type="text" required>
        </div>
        <div>
          <label for="date">Date</label>
          <input id="date" name="date" type="date" required>
        </div>
        <button type="submit">Add Task</button>
    </form>
    <hr>

<?php 
$servername = "db"; 
$username = "root";        
$password = "rootpassword";            
$dbname = "task_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// --- Insert Task ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = trim($_POST["task"]);
    $date = trim($_POST["date"]);

    if ($task == "" || $date == "") {
        echo "<p style='color:red;'>All fields are required.</p>";
    } else {
        $sql = "INSERT INTO tasks (task_name, task_date) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ss", $task, $date);
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Task added successfully!</p>";
            } else {
                echo "<p style='color:red;'>Error inserting task.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;'>SQL error: " . $conn->error . "</p>";
        }
    }
}

// --- View Tasks ---
echo "<h2>Task List</h2>";

$sql = "SELECT id, task_name, task_date, status FROM tasks ORDER BY task_date ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='8'>
            <tr>
                <th>ID</th>
                <th>Task</th>
                <th>Date</th>
                <th>Status</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['task_name']}</td>
                <td>{$row['task_date']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No tasks found.</p>";
}

mysqli_close($conn);
?>
<!-- ALTER TABLE tasks AUTO_INCREMENT = 1; -->
</body>
</html>
