<?php
session_start();
$root = "../Barpin/dic";
$currentPath = $_POST["path"] ?? $_SESSION["path"];
$directory = scandir($currentPath);
$_SESSION["directory"] = $directory;
$_SESSION["path"] = $currentPath;
?>

<!DOCTYPE HTML>
<html>
<head>

</head>
<body>
<?php

echo "Path: " . $currentPath . "<br>";

echo '<a href="close_session.php"> <button>Exit</button> </a><br>'; // Exit button directs the user to the home page.

$parentPath = dirname($currentPath, 1); // We use this path to go one level up in the file manager.
if ($parentPath != $root) {
    /* If parent path is the root path, don't show the "1 level up" button.
    Otherwise the user will see and access to other users' folders. */
    echo '<span style="display: inline;">' .
        '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">' .
        '<input type="hidden" name="path" value="' . $parentPath . '">' .
        '<input type="submit" name="submit" value="<< 1 level up">' .
        '</form></span>';
}

echo "<hr>";

/* Make a list of all the files or folders in directory array (this array is stored in $_SESSION["directory"]).
 There is a button next to each folder to enable us to go into the folder. */
foreach ($_SESSION["directory"] as $key => $fileOrFolderName) {
    if ($fileOrFolderName === "." || $fileOrFolderName === "..") {
        continue;
    }
    $path = $_SESSION["path"] . "/" . $fileOrFolderName;
    if (is_file($path)) {
        echo "<a href=$fileOrFolderName>" . $fileOrFolderName . "</a>" . "<br>";
    } else {
        echo $fileOrFolderName . ' ' .
            '<form style="display: inline;" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">' .
            '<input type="hidden" name="path" value="' . $path . '">' .
            '<input type="submit" name="submit" value=">>">' .
            '</form><br>';
    }
}
?>
</body>
</html>