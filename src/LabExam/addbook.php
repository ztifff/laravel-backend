<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
        color: #333;
        text-align: center;
    }
    form {
        display: inline-block;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
        
    }
    input {
        width: 100%;
        padding: 8px;
        margin: 5px 0 15px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    form button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        margin-bottom: 20px;
        max-width: 100%;
        width: 100%;
    }
    form button:hover {
        background-color: #45a049;
    }
    form button a {
        color: white;
        text-decoration: none;
    }
    h1 {
        margin-bottom: 20px;
    }
    .back {
        background-color: #008CBA;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
        margin-bottom: 20px;
    }
    .back:hover {
        background-color: #007B9E;
    }
    .back a {
        color: white;
        text-decoration: none;
    }


</style>

<body>
    <h1>Add a New Book</h1>

    <form action="" method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="author">Author:</label><br>
        <input type="text" id="author" name="author" required><br><br>

        <label for="published_date">Published Date:</label><br>
        <input type="number" id="year" name="year" 
        min="1000" max="2025" step="1" required><br><br>

        <label for="isbn">ISBN:</label><br>
        <input type="text" id="isbn" name="isbn" pattern="[0-9]{13}" placeholder="13-digit ISBN" required><br><br>


        <button type="submit">Add Book</button>
    </form><br><br>

    <button class="back"><a href="labexam.php">Back to Catalog Book</a></button>

    <?php 
    // Database connection
    $conn = new mysqli('db', 'root', 'rootpassword', 'books');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $conn->real_escape_string($_POST['title']);
        $author = $conn->real_escape_string($_POST['author']);
        $year = intval($_POST['year']);
        $isbn = $conn->real_escape_string($_POST['isbn']);

        $sql = "INSERT INTO books (title, author, year, isbn) 
        VALUES ('$title', '$author',  '$year', '$isbn')";

        // Execute the statement
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>New book added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
        }

        // Close connections
        $conn->close();
    }
    ?>
    
</body>
</html>