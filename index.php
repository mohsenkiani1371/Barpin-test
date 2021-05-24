<?php
session_start();

define('ROOT', "../Barpin/dic");
define('FILE_VIEWER_PATH', '/Barpin/file_viewer.php');

/**
 * Direct the user to the file viewer.
 * @param $path
 * @return bool Returns false if there is no such path.
 */
function goToFileViewer($path) {
    $directory = scandir($path); // Scan the path to find its files and folders
    if ($directory !== false) {
        /* Store the directory array (containing a list of files and folders) and the path
        in session variable to be used in the file viewer script. */
        $_SESSION["directory"] = $directory;
        $_SESSION["path"] = $path;
        header("Location: " . FILE_VIEWER_PATH);
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the html form is posted
    if (empty($_POST["id"])) { // Check if the id is set
        $error = "User ID is required";
    } elseif ($_POST["id"] == '.' || $_POST["id"] == '..' ) {
        $error = "There is no file or folder with ID '" . $_POST["id"] . "'";
    } else {
        $path = ROOT . '/' . $_POST["id"];
        if (goToFileViewer($path) === false) {
            $error = "There is no file or folder with ID '" . $_POST["id"] . "'";
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
