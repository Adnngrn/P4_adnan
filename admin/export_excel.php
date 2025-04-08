<?php
require '../connection.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Ambil tipe data dan rentang tanggal dari parameter
$type = $_GET['type'] ?? 'sales';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Tambahkan judul rentang tanggal di atas tabel
$title = "";
if ($type === 'sales') {
    $title = "Total Penjualan & Pendapatan Per Hari";
} elseif ($type === 'products') {
    $title = "Penjualan Per Produk";
} elseif ($type === 'categories') {
    $title = "Penjualan Per Kategori";
// } elseif ($type === 'income') {
//     $title = "Pendapatan Per Hari";
}

$sheet->setCellValue('A1', $title);
$sheet->setCellValue('A2', "Data dari tanggal: " . date('d M Y', strtotime($start_date)) . " hingga " . date('d M Y', strtotime($end_date)));

// Header tabel dimulai dari baris 4
$startRow = 4;

if ($type === 'sales') {
    $query = $pdo->prepare("
        SELECT 
            DATE(t.created_at) AS date, 
            SUM(td.quantity) AS total_sold, 
            SUM(t.final_price) AS total_income
        FROM transaction_details td
        JOIN transactions t ON td.transaction_id = t.id
        WHERE t.created_at BETWEEN ? AND ?
        GROUP BY DATE(t.created_at)
        ORDER BY date ASC
    ");
    $query->execute([$start_date, $end_date]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $sheet->setCellValue("A$startRow", 'No.');
    $sheet->setCellValue("B$startRow", 'Tanggal');
    $sheet->setCellValue("C$startRow", 'Total Produk Terjual');
    $sheet->setCellValue("D$startRow", 'Total Pendapatan (Rp)');

} elseif ($type === 'products') {
    $query = $pdo->prepare("
        SELECT p.name AS product_name, SUM(td.quantity) AS total_sold
        FROM transaction_details td
        JOIN products p ON td.product_id = p.id
        JOIN transactions t ON td.transaction_id = t.id
        WHERE t.created_at BETWEEN ? AND ?
        GROUP BY p.id
        ORDER BY total_sold DESC
    ");
    $query->execute([$start_date, $end_date]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $sheet->setCellValue("A$startRow", 'No.');
    $sheet->setCellValue("B$startRow", 'Nama Produk');
    $sheet->setCellValue("C$startRow", 'Total Terjual');

} elseif ($type === 'categories') {
    $query = $pdo->prepare("
        SELECT c.category_name AS category_name, SUM(td.quantity) AS total_sold
        FROM transaction_details td
        JOIN products p ON td.product_id = p.id
        JOIN categories c ON p.category_id = c.id
        JOIN transactions t ON td.transaction_id = t.id
        WHERE t.created_at BETWEEN ? AND ?
        GROUP BY c.id
        ORDER BY total_sold DESC
    ");
    $query->execute([$start_date, $end_date]);
    $data = $query->fetchAll(PDO::FETCH_ASSOC);

    $sheet->setCellValue("A$startRow", 'No.');
    $sheet->setCellValue("B$startRow", 'Kategori');
    $sheet->setCellValue("C$startRow", 'Total Terjual');

// } elseif ($type === 'income') {
//     $query = $pdo->prepare("
//         SELECT DATE(created_at) AS date, SUM(final_price) AS total_income
//         FROM transactions
//         WHERE created_at BETWEEN ? AND ?
//         GROUP BY DATE(created_at)
//         ORDER BY date ASC
//     ");
//     $query->execute([$start_date, $end_date]);
//     $data = $query->fetchAll(PDO::FETCH_ASSOC);

//     $sheet->setCellValue("A$startRow", 'No.');
//     $sheet->setCellValue("B$startRow", 'Tanggal');
//     $sheet->setCellValue("C$startRow", 'Total Pendapatan');
}

// Tambahkan data ke dalam Excel
$row = $startRow + 1;
$no = 1;
foreach ($data as $item) {
    $sheet->setCellValue("A{$row}", $no++);
    $sheet->setCellValue("B{$row}", $item['date'] ?? $item[array_keys($item)[0]]);
    $sheet->setCellValue("C{$row}", $item['total_sold'] ?? $item['total_income'] ?? $item[array_keys($item)[1]]);

    if ($type === 'sales') {
        $sheet->setCellValue("D{$row}", $item['total_income']);
    }

    $row++;
}

// Tambahkan total pendapatan jika income
// if ($type === 'income') {
//     $sheet->setCellValue("B{$row}", "Total Pendapatan:");
//     $sheet->setCellValue("C{$row}", "=SUM(C" . ($startRow + 1) . ":C" . ($row - 1) . ")");
//     $sheet->getStyle("B{$row}")->getFont()->setBold(true);
//     $sheet->getStyle("C{$row}")->getFont()->setBold(true);
//     $sheet->getStyle("C" . ($startRow + 1) . ":C{$row}")
//         ->getNumberFormat()
//         ->setFormatCode('"Rp"#,##0');
// }

// Format kolom pendapatan untuk sales
if ($type === 'sales') {
    $sheet->setCellValue("B{$row}", "Total:");
    $sheet->setCellValue("C{$row}", "=SUM(C" . ($startRow + 1) . ":C" . ($row - 1) . ")");
    $sheet->setCellValue("D{$row}", "=SUM(D" . ($startRow + 1) . ":D" . ($row - 1) . ")");

    $sheet->getStyle("B{$row}:B" . ($row + 1))->getFont()->setBold(true);
    $sheet->getStyle("C{$row}:D" . ($row + 1))->getFont()->setBold(true);

    // Format total pendapatan dalam bentuk Rupiah
    $sheet->getStyle("D" . ($startRow + 1) . ":D" . ($row + 1))
        ->getNumberFormat()
        ->setFormatCode('"Rp"#,##0');
}

// Format tampilan umum
$mergeEnd = $type === 'sales' ? 'D' : 'C';
$sheet->mergeCells("A1:{$mergeEnd}1");
$sheet->mergeCells("A2:{$mergeEnd}2");
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A2')->getFont()->setBold(true);
$sheet->getStyle("A$startRow:{$mergeEnd}$startRow")->getFont()->setBold(true);

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
if ($type === 'sales') {
    $sheet->getColumnDimension('D')->setAutoSize(true);
}

// Simpan file sebagai Excel
$filename = strtolower(str_replace(' ', '_', $title)) . "_" . date('Y-m-d') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
