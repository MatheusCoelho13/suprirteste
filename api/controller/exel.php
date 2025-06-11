<?php
// Carregar o autoloader do Composer
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


require "../db/db.php";
// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultar os dados
$sql = "SELECT id, first_name, last_name, phone, email, file_path, complemento, servico, lugar,  created_at FROM contacts";
$result = $conn->query($sql);

// Criar uma nova planilha
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Definir o cabeçalho da planilha
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'First Name');
$sheet->setCellValue('C1', 'Last Name');
$sheet->setCellValue('D1', 'Phone');
$sheet->setCellValue('E1', 'email');
$sheet->setCellValue('F1', 'Complemento');
$sheet->setCellValue('G1', 'Servico');
$sheet->setCellValue('H2', 'local');


// Preencher a planilha com os dados do banco
$rowNum = 2; // Começar a partir da segunda linha
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id']);
    $sheet->setCellValue('B' . $rowNum, $row['first_name']);
    $sheet->setCellValue('C' . $rowNum, $row['last_name']);
    $sheet->setCellValue('D' . $rowNum, $row['phone']);
    $sheet->setCellValue('E' . $rowNum, $row['complemento']);
    $sheet->setCellValue('F' . $rowNum, $row['servico']);
    $sheet->setCellValue('G' . $rowNum, $row['lugar']);
    $rowNum++;
}

// Criar o arquivo Excel
$writer = new Xlsx($spreadsheet);

// Definir o nome do arquivo
$fileName = 'dados_contatos.xlsx';

// Salvar o arquivo Excel para download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');

// Fechar a conexão
$conn->close();
