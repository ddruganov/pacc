<?php

namespace api\controllers\actions\statistics;

use core\actions\ApiAction;
use core\collectors\statistics\StatisticsViewCollector;
use core\components\ExecutionResult;
use core\components\helpers\UserHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StatisticsDownloadAction extends ApiAction
{
    public function run()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (!isset($data['id'])) {
            return $this->apiResponse(new ExecutionResult(false, ['common' => 'Отсутствуют входные данные']));
        }

        $statisticsData = (new StatisticsViewCollector())->setParams([
            'id' => $data['id'],
            'organizationId' => UserHelper::getOrganizationId()
        ])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', $statisticsData['name']);

        // header
        $cellLetterAsInt = ord('A');
        foreach ($statisticsData['fields'] as $key => $field) {
            $cellCoordinates = chr($cellLetterAsInt) . '1';
            $sheet->setCellValue($cellCoordinates, $field['alias']);
            $sheet->getStyle($cellCoordinates)->applyFromArray(['font' => ['bold' => true]]);
            $cellLetterAsInt++;
        }

        // data
        foreach ($statisticsData['data'] as $key => $entryValues) {
            $cellLetterAsInt = ord('A');
            foreach (array_values($entryValues) as $value) {
                $cellCoordinates = chr($cellLetterAsInt) . ($key + 2);
                $sheet->setCellValue($cellCoordinates, $value);
                $cellLetterAsInt += 1;
            }
        }

        // make all columns fit to width
        for ($i = 'A'; $i <= $sheet->getHighestColumn(); $i++) {
            $sheet->getColumnDimension($i)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $stream = base64_encode(ob_get_contents());
        ob_end_clean();

        return $this->apiResponse(new ExecutionResult(true, [], ['bytes' => $stream]));
    }
}
