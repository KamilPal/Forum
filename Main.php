<!DOCTYPE html>
<html lang="pl">

<head>
    <title>Forum</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="Forum.css">
</head>

<body>
    <header>
        <h2>Forum dyskusyjne</h2>
    </header>
    <section>
        <div class="Add">
            <form action="zapisywanie.php" method="POST">
                <p>Wprowadź nazwę użytkownika: <input type="text" name="name" required></p>
                <p style="text-align: center;">Rozpocznij dyskusję:</p>
                <textarea name="komentarz" placeholder="Na przykład: Dlaczego niebo jest niebieskie?" required></textarea>
                <input type="hidden" name="data" value="<?php echo date('Y-m-d'); ?>">
                <p style="text-align: center;"><input type="submit" value="Umieść dyskusję"></p>
            </form>
        </div>
        <article>
            <div class="topdys">
                <h2 style="text-align: center; margin-bottom: 5px;">Trwające dyskusje</h2>
                <form action="" method="GET">
                    <label for="sort">Sortuj według:</label>
                    <select name="sort" id="sort">
                        <option value="data">Daty (Najnowsze)</option>
                        <option value="data_desc">Daty (Najstarsze)</option>
                    </select>
                    <input type="submit" value="Sortuj">
                </form>
            </div>
            <div class="php_article">
                <?php
                if (file_exists("dane.txt")) {
                    $file = fopen("dane.txt", "r+");
                    if ($file) {
                        $sixMonthsAgo = strtotime('-6 months');
                        $newContent = '';
                        $discussions = array();

                        while (($line = fgets($file)) !== false) {
                            $lineData = explode('|', $line);

                            if (count($lineData) >= 3) {
                                $postTimestamp = strtotime($lineData[2]);

                                if ($postTimestamp >= $sixMonthsAgo) {
                                    $newContent .= $line;
                                }

                                $discussions[] = array(
                                    'data' => $lineData[2],
                                    'uzytkownik' => $lineData[0],
                                    'opis' => $lineData[1]
                                );
                            }
                        }

                        ftruncate($file, 0);
                        rewind($file);

                        fwrite($file, $newContent);

                        fclose($file);

                        if (empty($discussions)) {
                            echo '<p style="text-align: center;margin-top:5px;">W tym momencie nie ma żadnych dyskusji.</p>';
                        } else {
                            if (isset($_GET['sort'])) {
                                $sortOption = $_GET['sort'];
                                if ($sortOption == 'data') {
                                    usort($discussions, function ($a, $b) {
                                        return strtotime($b['data']) <=> strtotime($a['data']);
                                    });
                                } elseif ($sortOption == 'data_desc') {
                                    usort($discussions, function ($a, $b) {
                                        return strtotime($a['data']) <=> strtotime($b['data']);
                                    });
                                }
                            }

                            foreach ($discussions as $discussion) {
                                echo "<p style='margin-bottom:5px; margin-top:5px;'>Data: {$discussion['data']}</p>";
                                echo "<p style='margin-top:5px;margin-bottom:5px;color:#549d5a;font-weight:bold;'>Użytkownik: {$discussion['uzytkownik']}</p>";
                                echo "<p style='margin-bottom:5px;'>{$discussion['opis']}</p>";
                                echo "<hr>";
                            }
                        }
                    } else {
                        echo "Błąd: Nie udało się otworzyć pliku.";
                    }
                } else {
                    echo "Plik 'dane.txt' nie istnieje.";
                }
                ?>
            </div>
        </article>
    </section>

    <footer>
        <p>Posty starsze niż 6 miesięcy zostają automatycznie usuwane.</p>
    </footer>
</body>

</html>
