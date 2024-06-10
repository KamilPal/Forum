<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    if (empty($_POST["name"]) || empty($_POST["komentarz"])) {
        echo "Wprowadź dane";
    } else {
        $name = $_POST['name'];
        $kom = $_POST['komentarz'];
        $data = date('d-m-Y');

        $file = fopen("dane.txt", "a");

        fwrite($file, "$name|$kom|$data\n");

        fclose($file);
        echo "Dane zostały pomyślnie zapisane.";
        header("Location: Main.php");
        exit();
    }
}
?>
