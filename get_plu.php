<?php
$file = "carga/ITENSMGV.txt";
$pluList = [];

if (file_exists($file)) {
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $num_plu = trim(substr($line, 0, 10));
        $nome_plu = trim(substr($line, 10, 40));
        $preco = trim(substr($line, 50, 10)) / 100;

        $pluList[] = [
            "num_plu" => $num_plu,
            "nome_plu" => $nome_plu,
            "preco" => number_format($preco, 2, ',', '')
        ];
    }
}
echo json_encode($pluList);
?>
