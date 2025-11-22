<?php
$host = "db";
$user = "root";
$pass = "rootpassword";
$db   = "library_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_by = isset($_GET['search_by']) ? $_GET['search_by'] : 'title';

$allowed_fields = ['id', 'title', 'author', 'publisher', 'isbn', 'year_published'];
$results = [];

if ($query !== "" && in_array($search_by, $allowed_fields)) {
    if ($search_by === 'id' || $search_by === 'year_published') {
        $stmt = $conn->prepare("SELECT * FROM books WHERE $search_by = ?");
        $stmt->bind_param("i", $query);
    } else {
        $stmt = $conn->prepare("SELECT * FROM books WHERE $search_by LIKE ?");
        $like = "%" . $query . "%";
        $stmt->bind_param("s", $like);
    }
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Search Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="header">Search Books</h1>

    <form method="get" class="search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Enter search..." required>
        <select name="search_by" required>
            <option value="id" <?= ($search_by=='id') ? 'selected' : '' ?>>ID</option>
            <option value="title" <?= ($search_by=='title') ? 'selected' : '' ?>>Title</option>
            <option value="author" <?= ($search_by=='author') ? 'selected' : '' ?>>Author</option>
            <option value="publisher" <?= ($search_by=='publisher') ? 'selected' : '' ?>>Publisher</option>
            <option value="isbn" <?= ($search_by=='isbn') ? 'selected' : '' ?>>ISBN</option>
            <option value="year_published" <?= ($search_by=='year_published') ? 'selected' : '' ?>>Year Published</option>
        </select>
        <button type="submit">Search</button>
        <a href="index.php" class="btn add">Back to Catalog</a>
    </form>

<?php if ($query !== ""): ?>
    <h2>Results for "<?= htmlspecialchars($query) ?>" in <?= htmlspecialchars($search_by) ?></h2>

    <?php if ($results && $results->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $results->fetch_assoc()): ?>
                <?php
                    
                    $statusLabel = $row['available'] ? "Available" : "Borrowed";

                    if ($row['available']) {
                        $actionButton = "<a href='borrow.php?id={$row['id']}' class='borrow'>Borrow</a>";
                    } else {
                        $actionButton = "<a href='return.php?id={$row['id']}' class='return'>Return</a>";
                    }
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['publisher']) ?></td>
                    <td><?= htmlspecialchars($row['isbn']) ?></td>
                    <td><?= htmlspecialchars($row['year_published']) ?></td>
                    <td><?= $statusLabel ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class='edit'>Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                <?= $actionButton ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No books found.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
