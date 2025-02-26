<?php
// Caminho do arquivo
$arquivo = 'carga/ITENSMGV.txt';

// Abre o arquivo para leitura
$linhas = file($arquivo);

// Array para armazenar os dados dos itens
$itens = [];

// Percorre cada linha do arquivo
foreach ($linhas as $linha) {
    // Extrai os campos conforme o layout do arquivo de itens
    $codigoItem = substr($linha, 3, 6); // Código do Item
    $codigoItem = ltrim($codigoItem, '0'); // Remove zeros à esquerda

    $descritivoItem = trim(substr($linha, 18, 25)); // Descritivo do Item 
    $preco = substr($linha, 11, 4); // Preço/kg ou Preço/Unid.

    // Formata o preço (divide por 100 para obter o valor correto)
    $precoFormatado = number_format((float)$preco / 100, 2, ',', '.');

    // Adiciona os dados ao array de itens
    $itens[] = [
        'codigo' => $codigoItem,
        'descritivo' => $descritivoItem,
        'preco' => $precoFormatado
    ];
}

// Retorna os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($itens);
?>