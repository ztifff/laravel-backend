<?php
// ✅ Show submitted data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<h2>Submitted Data:</h2><pre>";
    print_r($_POST);
    echo "</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Attributes Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, textarea { margin-bottom: 10px; padding: 5px; width: 250px; }
    </style>
</head>
<body>
    <h1>📑 HTML Form Attributes Demo</h1>

    <form method="POST" action="">

        <!-- Basic Identification -->
        <label for="username">Username (id, name, placeholder, maxlength)</label>
        <input type="text" id="username" name="username" placeholder="Enter username" maxlength="15">

        <label>Password (type, required)</label>
        <input type="password" name="password" required>

        <!-- Data Rules & Restrictions -->
        <label>Age (number, min, max, step)</label>
        <input type="number" name="age" min="18" max="100" step="1">

        <label>ISBN (pattern, placeholder)</label>
        <input type="text" name="isbn" placeholder="13-digit ISBN" pattern="[0-9]{13}">

        <label>Upload Image (accept)</label>
        <input type="file" name="profile_pic" accept="image/*">

        <!-- Behavior -->
        <label>Read Only Field (readonly)</label>
        <input type="text" name="readonly_example" value="Cannot change me" readonly>

        <label>Disabled Field (disabled)</label>
        <input type="text" name="disabled_example" value="Not submitted" disabled>

        <label>Newsletter (checkbox, checked)</label>
        <input type="checkbox" name="newsletter" value="yes" checked> Subscribe me

        <label>Gender (radio, checked)</label>
        <input type="radio" name="gender" value="male" checked> Male
        <input type="radio" name="gender" value="female"> Female

        <!-- User Experience -->
        <label for="country">Country (datalist)</label>
        <input list="countries" name="country" id="country">
        <datalist id="countries">
            <option value="Philippines">
            <option value="USA">
            <option value="Japan">
            <option value="Canada">
        </datalist>

        <label for="color">Favorite Color (select, multiple, size)</label>
        <select name="colors[]" id="color" multiple size="3">
            <option value="red" selected>Red</option>
            <option value="blue">Blue</option>
            <option value="green">Green</option>
        </select>

        <label>Comments (textarea, spellcheck)</label>
        <textarea name="comments" spellcheck="true" placeholder="Type your comments here..."></textarea>

        <!-- Security & Special -->
        <label>Email (inputmode, autocomplete)</label>
        <input type="text" name="email" inputmode="email" autocomplete="on">

        <br><br>

        <input type="text" accept=".docx">

        <!-- Submit Buttons -->
        <input type="submit" value="Submit Normally">
        <input type="submit" value="Submit w/o Validation" formnovalidate>
    </form>
</body>
</html>
