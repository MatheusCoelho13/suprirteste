<?php
header('Content-Type: application/json');

// Verifica se o CEP foi enviado via GET
if (!isset($_GET['cep'])) {
    echo json_encode(['erro' => 'CEP não informado']);
    exit;
}

$cep = preg_replace('/[^0-9]/', '', $_GET['cep']); // limpa caracteres não numéricos

if (strlen($cep) != 8) {
    echo json_encode(['erro' => 'CEP inválido. Deve conter 8 dígitos.']);
    exit;
}

$url = "https://viacep.com.br/ws/$cep/json/";

$response = file_get_contents($url);

if (!$response) {
    echo json_encode(['erro' => 'Erro ao acessar o ViaCEP']);
    exit;
}

$data = json_decode($response, true);

if (isset($data['erro'])) {
    echo json_encode(['erro' => 'CEP não encontrado']);
    exit;
}

// Retorna os dados do endereço em JSON
echo json_encode([
    'cep' => $data['cep'],
    'logradouro' => $data['logradouro'],
    'complemento' => $data['complemento'],
    'bairro' => $data['bairro'],
    'cidade' => $data['localidade'],
    'estado' => $data['uf']
]);
