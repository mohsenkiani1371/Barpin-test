<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the html form is posted
    if (empty($_POST["id"])) { // Check if the id is set
        $error = "User ID is required";
    } else {
        $root = "../Barpin/dic/";
        $id = $_POST["id"];
        $path = $root . $id;
        $directory = scandir($path); // Scan the path to find its files and folders
        if ($directory !== false) {
            /* Save the values of directory (an array of files and folders) and path in session variable
             to be used in the file manager script */
            $_SESSION["directory"] = $directory;
            $_SESSION["path"] = $path;
            header("Location: /Barpin/file_manager.php");
        } else {
            $error = "There is no file or folder with ID $id";
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
    </style>
</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Enter the user ID here: <input type="text" name="id">
    <span class="error">* <?php echo $error;?></span>
    <br>
    <input type="submit" name="submit" value="Submit">
</form>
</body>
</html>
