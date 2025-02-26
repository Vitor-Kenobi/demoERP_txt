<?php
// Caminho do arquivo
$arquivo = 'carga/ITENSMGV.txt';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Método de requisição inválido.']));
}

// Decodifica os dados JSON recebidos
$data = json_decode(file_get_contents('php://input'), true);

// Verifica se todos os campos necessários foram fornecidos
if (!isset($data['plu']) || !isset($data['nome_plu']) || !isset($data['preco']) || 
    !isset($data['un_medida']) || !isset($data['num_texto']) || !isset($data['num_nutricao'])) {
    die(json_encode(['error' => 'Dados incompletos.']));
}

// Formata o código do PLU com zeros à esquerda
$plu = str_pad($data['plu'], 6, '0', STR_PAD_LEFT);

// Abre o arquivo para leitura e escrita
$linhas = file($arquivo);

// Percorre cada linha do arquivo
foreach ($linhas as &$linha) {
    // Extrai o código do item da linha
    $codigoItem = substr($linha, 3, 6); // Código do Item

    // Verifica se o código do item corresponde ao PLU selecionado
    if ($codigoItem == $plu) {
        // Atualiza os campos da linha
        $linha = substr_replace($linha, str_pad($data['num_nutricao'], 6, '0', STR_PAD_LEFT), 68, 6); // Nº da Nutrição
        $linha = substr_replace($linha, str_pad($data['num_texto'], 6, '0', STR_PAD_LEFT), 78, 6); // Nº do Texto Extra
        $linha = substr_replace($linha, $data['un_medida'] == 'PCS' ? '1' : '0', 2, 1); // Unidade de Medida
        $linha = substr_replace($linha, str_pad(str_replace(',', '', $data['preco']), 4, '0', STR_PAD_LEFT), 11, 4); // Preço
        $linha = substr_replace($linha, str_pad($data['nome_plu'], 25, ' ', STR_PAD_RIGHT), 18, 25); // Descritivo do Item
        break;
    }
}

// Salva as alterações no arquivo
file_put_contents($arquivo, implode("", $linhas));

// Retorna uma mensagem de sucesso
echo json_encode(['success' => 'PLU atualizado com sucesso.']);
?>