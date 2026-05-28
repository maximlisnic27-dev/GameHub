<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Exercițiu PHP</title>
</head>
<body>

<h1>Numere Pare și Impare</h1>

<?php

$numere = [12, 7, 5, 18, 21, 30, 44, 9, 2, 15];

$pare = 0;
$impare = 0;

for ($i = 0; $i < 10; $i++) {

    echo $numere[$i];

    if ($numere[$i] % 2 == 0) {
        echo " - număr par <br>";
        $pare++;
    } else {
        echo " - număr impar <br>";
        $impare++;
    }
}

echo "<hr>";

echo "Total numere pare: " . $pare . "<br>";
echo "Total numere impare: " . $impare;

?>

<script>
    console.log("Exercițiul PHP a fost executat!");
</script>

</body>
</html>