<?php
session_start();
?>

<!DOCTYPE HTML>
<html>
<head>

</head>
<body>
<?php
$root = "../Barpin/dic";
$currentPath = $_POST["path"] ?? $_SESSION["path"];
$_SESSION["path"] = $currentPath;
$parentPath = dirname($currentPath, 1); // target path for the "Back" button, used to go one level up on the directory tree.
$directory = scandir($currentPath);
$_SESSION["directory"] = $directory;

function addExitButton() {
    echo '<a href="close_session.php"> <button>Exit</button> </a><br>';
}

function addBackButton($target) {
    echo '<span style="display: inline;">' .
        '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">' .
        '<input type="hidden" name="path" value="' . $target . '">' .
        '<input type="submit" name="submit" value="<< Back">' .
        '</form></span>';
}

function addForwardButton($target) {
    echo '<form style="display: inline;" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">' .
        '<input type="hidden" name="path" value="' . $target . '">' .
        '<input type="submit" name="submit" value="Go inside >>">' .
        '</form><br>';
}

function removeElement($value, &$array) {
    $key = array_search($value, $array);
    unset($array[$key]);
}

/**
 * Make a list of all the files or folders in directory array (this array is stored in $_SESSION["directory"]).
 *
 * @param array $directory
 */
function listFilesAndFolders(Array $directory) {
    global $currentPath;

    removeElement('.', $directory);
    removeElement('..', $directory);

    if (count($directory) == 0) {
        echo '(Folder is empty)';
    } else {
        foreach ($directory as $key => $fileOrFolderName) {
            if ($fileOrFolderName === "." || $fileOrFolderName === "..") {
                continue;
            }
            $targetPath = $currentPath . "/" . $fileOrFolderName;
            if (is_file($targetPath)) {
                echo "<a href=$fileOrFolderName>" . $fileOrFolderName . "</a>" . "<br>";
            } else {
                echo $fileOrFolderName . ' ';
                addForwardButton($targetPath); // A button is added next to each folder to enable us to go into the folder.
            }
        }
    }
}

/**
 * Show the file viewer content, including a list of files and folders and exit, back and forward buttons.
 */
function showFileViewerContent() {
    global $currentPath;
    global $root;
    global $directory;
    global $parentPath;

    echo "Path: " . $currentPath . "<br>";

    addExitButton(); // Exit button directs the user to the home page.

    /* If parent path is the root path, we don't show the "Back" button, because if we do so, the user will see
    other users' folders and have access to them. */
    if ($parentPath != $root) {
        addBackButton($parentPath);
    }

    echo "<hr>";

    listFilesAndFolders($directory);
}

showFileViewerContent();

?>
</body>
</html>