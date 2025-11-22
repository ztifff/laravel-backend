<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f4;
        color: #333;
        text-align: center;
    }

    button {
        margin: 5px;
        outline: none;
        border: none;
    }

    button.addbook {
        background-color: #008CBA;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 20px;
    }
    button.addbook a {
        color: white;
        text-decoration: none;
    }
    button.addbook:hover {
        background-color: #007B9E;
    }

    form button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    form button a {
        color: white;
        text-decoration: none;
    }

    form button:hover {
        background-color: #45a049;
    }

    form {
        margin-top: 20px;
    }

    input[type="text"] {
        padding: 10px;
        width: 300px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    form button[type="submit"] {
        padding: 10px 15px;
        font-size: 16px;
        margin-left: 10px;
    }

    p {
        font-size: 18px;
    }

    h1 {
        color: #4CAF50;
        font-size: 36px;
        margin-bottom: 10px;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    table .delete-btn {
        background-color: #f44336;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        text-decoration: none;
    }
    table .delete-btn:hover {
        background-color: #d32f2f;
    }
</style>

<body>
    <h1>Library Catalog</h1>
    <p>Welcome to the Library Catalog. Explore our collection of books and resources.</p>

    <button class="addbook"><a href="addbook.php">Add Book</a></button>

    <form action="" method="GET">
        <input type="text" placeholder="Search by Title, Author, or ISBN" name="search"
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" value="search">Search</button>
        <button><a href="labexam.php">Reset</a></button>
    </form>

    <?php
    $conn = mysqli_connect('db', 'root', 'rootpassword', 'books');
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Handle deletion
    if (isset($_GET['delete'])) {
        $delete_id = intval($_GET['delete']);
        $conn->query("DELETE FROM books WHERE id=$delete_id");

        $conn->query("SET @count = 0");
        $conn->query("UPDATE books SET id = @count := @count + 1 ORDER BY id");
        $conn->query("ALTER TABLE books AUTO_INCREMENT = 1");
        
        echo "<p>Book deleted successfully.</p>";
    }

    $search = "";
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR ISBN LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM books";
    }

    $result = $conn->query($sql);
    echo "<h2>Book List</h2>";

    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Year Published</th>
        <th>ISBN</th>
        <th>Action</th>
    </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
        <td>" . ($row["id"]) . "</td>
         <td>" . ($row["title"]) . "</td>
         <td>" . ($row["author"]) . "</td>
         <td>" . ($row["year"]) . "</td>
         <td>" . ($row["ISBN"]) . "</td>
            <td><button><a class='delete-btn' href='labexam.php?delete=" . $row["id"] . "'
            onclick=\"return confirm('Are you sure you want to delete this book?');\">Delete</a></button>
         </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No books found.</p>";
    }

    mysqli_close($conn);

    ?>

</body>

</html>