<?php
namespace app\controllers;
use Yii;

class ToolController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionTestXls(){
      $html = $this->renderFile('/media/DATA/app/PHP/YII2/ro/test.html');
      return $this->printXls2($html, false, ['title' =>'qqqq']);
    }

    public function printXls2($html=null, $isReport=false, $setting=null) {
		$tmpfile = Yii::$app->basePath . "/tmp/" . time() . '.html';
    file_put_contents($tmpfile, $html);
    $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Html');
    $spreadsheet = $objReader->load('/media/DATA/app/PHP/YII2/ro/test.html');
    unlink($tmpfile);
		$filename = isset($setting['filename']) ? $setting['filename'] : $setting['title'];
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
    header('Content-Disposition: attachment;filename="' . trim($filename) . '.xls"'); // specify the download file name
    header('Cache-Control: max-age=0');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet); 
    $writer->save('php://output');
    exit;
  }

  public function actionPrintPdf(){
		$html = $this->renderFile('/media/DATA/app/PHP/YII2/ro/runtime/test.html');
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
      header('Content-Disposition: attachment; filename="test.pdf";');
      echo $pdf;
    }
	}
}
