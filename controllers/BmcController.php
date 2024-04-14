<?php
namespace app\controllers;

use Yii;
use app\models\Userlog;
use Exception;

class BmcController extends LogController {

	public function areaAccess($unit){
		if(!$unit) return;
		$iden = \Yii::$app->session->get('identity'); 
		if(!$iden) return;
		return in_array($unit, $iden['areaAccess']);
	}
	
	public function printPdf($filename, $html){
    $descriptorspec = array(
          0 => array('pipe', 'r'), // stdin
          1 => array('pipe', 'w'), // stdout
          2 => array('pipe', 'w'), // stderr
        );
        $iden = Yii::$app->user->getIdentity();
				$username = $iden ? $iden->username : 'Guest';
        // Linux
        $process = proc_open('wkhtmltopdf -q --javascript-delay 1000 - -', $descriptorspec, $pipes);
        // Send the HTML on stdin
        fwrite($pipes[0], $html);
        fclose($pipes[0]);

        // Read the outputs
        $pdf = stream_get_contents($pipes[1]);
        $errors = stream_get_contents($pipes[2]);
        // Close the process
        fclose($pipes[1]);
        $return_value = proc_close($process);
        if ($errors) {
          throw new Exception('PDF generation failed: ' . $errors);
        } else {
          header('Content-Type: application/pdf');
          header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
          header('Pragma: public');
          header('Expires: 0'); // Date in the past
          //header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
          header('Content-Length: ' . strlen($pdf));
          header('Content-Transfer-Encoding: binary');
          header('Content-Disposition: attachment; filename="' . $filename . '.pdf";');
          echo $pdf;
        }
  }

  public function printXls($html=null, $isReport=false, $setting=null) {
    $tmpfile = Yii::$app->basePath . "/tmp/" . time() . '.html'; 
    file_put_contents($tmpfile, $html);
    $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Html');
    $spreadsheet = $objReader->load($tmpfile);
    $sheet = $spreadsheet->getActiveSheet();
		$protect = $sheet->getProtection();
		$protect->setPassword('admin123');
		$protect->setSheet(true);
    $maxRow = $sheet->getHighestRow(); // find max row
    $maxCol = $sheet->getHighestColumn(); // find max col
    $maxColAsNum = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxCol);
    $offsetMaxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($maxColAsNum + 1);
    
		$startCell = $isReport ? 'A5:' : 'A2:';
		$tableArea = $startCell . $maxCol . $maxRow;
		if($isReport){
			$theadArea = $startCell . $maxCol . '6';
			$sheet->setCellValue('A1', $setting['title']);
			$sheet->setCellValue('A2', 'Depeartement ');
			$sheet->setCellValue('B2', $setting['dept']);
			$sheet->setCellValue('A3', 'Area');
			$sheet->setCellValue('B3', $setting['area']);
			$sheet->setCellValue('A4', 'Date');
			$sheet->setCellValue('B4', $setting['date']);
			/* FORMAT TD */
			$sheet->getStyle('A5:A' . $maxRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}else{
			$theadArea = $startCell . $maxCol . '2';
			/* FORMAT TD */
			$sheet->getStyle('A3:A' . $maxRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}
    
		# STYLE AREA
		$titleStyle = [
			'font' => ['bold' => true],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      ],
		];
    $tableAreaStyle = [
      'borders' => [
        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
      ]
    ];
    # FORMAT HEADER
    $headerstyl = array(
      'font' => ['size' => 10, 'bold' => true],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'wrapText' => true
      ],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        //'rotation' => 90,
        'startColor' => array('rgb' =>'8fbdf7'), 
        'endColor' => array('rgb' =>'FFFFFFFF'),
      ]
    );
		
		/* SET TITLE */
		$sheet->mergeCells('A1:'.$maxCol.'1');
		$sheet->getStyle('A1')->applyFromArray($titleStyle);
    $sheet->getStyle($theadArea)->applyFromArray($headerstyl);
    $sheet->getStyle($tableArea)->applyFromArray($tableAreaStyle);

    for ($col = 'A'; $col != $offsetMaxCol; ++$col) {
      $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    unlink($tmpfile);
		$filename = isset($setting['filename']) ? $setting['filename'] : $setting['title'];
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
    header('Content-Disposition: attachment;filename="' . trim($filename) . '.xls"'); // specify the download file name
    //header('Content-Disposition: attachment;filename="xxx.xlsx"'); 
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

    $writer->save('php://output');
    exit;
  }
	
}

?>
