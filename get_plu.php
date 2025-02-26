<?php
// Caminho do arquivo
$arquivo = 'carga/ITENSMGV.txt';

// Verifica se o parâmetro "plu" foi passado
if (!isset($_GET['plu'])) {
    die(json_encode(['error' => 'Código do PLU não fornecido.']));
}

$plu = $_GET['plu']; // Código do PLU

// Abre o arquivo para leitura
$linhas = file($arquivo);

// Percorre cada linha do arquivo
foreach ($linhas as $linha) {
    // Extrai o código do item da linha
    $codigoItem = substr($linha, 3, 6); // ID PLU
    $codigoItem = ltrim($codigoItem, '0'); // Remove zeros à esquerda

    // Verifica se o código do item corresponde ao PLU selecionado
    if ($codigoItem == $plu) {
        // Extrai os campos necessários
        $descritivoItem = trim(substr($linha, 18, 25)); // Descritivo do Item
        $preco = substr($linha, 11, 4); // Preço/kg ou Preço/Unid.
        $precoFormatado = number_format((float)$preco / 100, 2, ',', '.'); // Formata o preço
        $unid_medida = substr($linha, 2, 1); // Unidade de medida, kg ou unid
        $unid_medida = ($unid_medida == '1') ? 'PCS' : 'KGM';

        //Verifica se o ET é zero
        $n_et = substr($linha, 78, 6);
        if ($n_et != 0) {
            $n_et = ltrim($n_et, '0');
        } else {
            $n_et = 0;
        }
        
        //Verifica se o Nutrição é zero
        $n_nutri = substr($linha, 68, 6);
        if ($n_nutri != 0){
            $n_nutri = ltrim($n_nutri, '0');
        } else {
            $n_nutri = 0;
        }

        // Retorna os dados em formato JSON
        header('Content-Type: application/json');
        echo json_encode([
            'codigo' => $codigoItem,
            'descritivo' => $descritivoItem,
            'preco' => $precoFormatado,
            'un_medida' => $unid_medida, 
            'num_texto' => $n_et, 
            'num_nutricao' => $n_nutri
        ]);
        exit; // Encerra o script após encontrar o PLU
    }
}

// Se o PLU não for encontrado, retorna um erro
header('Content-Type: application/json');
echo json_encode(['error' => 'PLU não encontrado.']);
?>