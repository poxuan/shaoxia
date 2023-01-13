<?php

namespace App\Service;

// use App\Traits\ExcelRules;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelService
{
    //  use ExcelRules; //额外规则

    public $error_lines = [];  //错误行错误原因
    public $update_lines = []; //更新行数据

    const OUT_FILE = 1;
    const OUT_STREAM = 2;

    private $offset = 0;      //当前读取行数
    private $limit = 1000;   //每次读取行数
    private $hasMore = true;   //还有更多数据
    private $type = 'Xlsx'; //默认文档类型

    /**
     * ExcelService constructor.
     *
     * @param int $limit 每次读取行数
     */
    public function __construct($type = 'Xlsx', $limit = 1000)
    {
        if (!in_array(ucfirst($type), ['Xlsx', 'Xls', 'Csv'])) {
            throw new \Exception('文件类型不支持！');
        }
        $this->type = ucfirst($type);
        $this->limit = $limit;
    }

    /**
     * 从EXCEL文档中获取指定数据
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-06 19:31:08
     * @param string $file 文件
     * @param array $keys 顺序读取的键,
     * @param int $rowStart 开始读取行
     * @param int $columnStart 开始读取列
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function getDataFromExcel($file, $keys, $rowStart = 2, $columnStart = 1, $sheet = 0)
    {
        $reader = IOFactory::createReader($this->type);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file); //载入excel表格
        if ($sheet) {
            $worksheet = $spreadsheet->getSheet($sheet);
        } else {
            $worksheet = $spreadsheet->getActiveSheet();
        }
        $highestRow = $worksheet->getHighestRow(); // 总行数
        //$highestColumn = $worksheet->getHighestColumn(); // 总列数

        // 剩余行数
        $rows = $highestRow - $rowStart - $this->offset;
        if ($rows < $this->limit) { // 不够分两页，即设置没有更多。
            $this->hasMore = false;
        }
        // 开始读取行
        $start = $rowStart + $this->offset;
        // 下标增加
        $this->offset += $this->limit;
        // 结束行
        $end = min($highestRow, $this->offset + $rowStart);
        $data = [];
        for ($row = $start; $row <= $end; $row++) {
            $l = array();
            foreach ($keys as $k => $v) {
                $value = $worksheet->getCell([$k + $columnStart, $row])->getValue();
                $value = trim($value);
                if ($value !== null && $value !== '') {
                    $value = str_replace(['\n', '\r', '\t'], ["\n", "\r", "\t"], $value);
                    $l[$v] = $value;
                }
            }
            if ($l) {
                $data[$row] = $l;
            } else {
                $this->error_lines[] = '第 ' . $row . ' 行 为空行';
            }
        }
        return $data;
    }

    /**
     * 获取导入数据
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-07 09:57:05
     * @param string $file_path       //路径
     * @param array $columns          // 列二维数组
     * @param string $validator_class //验证类
     * @param string $validator_func  //验证类方法
     * @param int $rowStart //数据开始行
     * @param int $columnStart //数据结束行
     * @param int $sheet //数据页
     * @return array|null
     */
    public function importData($file_path, $columns, $rowStart = 2, $columnStart = 1, $sheet = 0)
    {
        if (is_file($file_path)) {
            try {
                $column_keys = array_keys($columns);
                $data = $this->getDataFromExcel($file_path, $column_keys, $rowStart, $columnStart, $sheet);
                return $data;
            } catch (\Exception $e) {
                $this->hasMore = false;
                return null;
            }
        }
        return null;
    }

    /**
     * 获取所有表单的数据
     *
     * @param [type] $file
     * @param integer $rowStart
     * @param integer $columnStart
     * @return array
     * @author chentengfei
     * @since
     */
    public function getAllSheets($file, $rowStart = 0, $columnStart = 1)
    {
        $reader = IOFactory::createReader($this->type);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file); //载入excel表格
        $worksheets = $spreadsheet->getAllSheets();
        $result = [];
        foreach ($worksheets as $worksheet) {
            $this->offset = 0;
            //$worksheet = $spreadsheet->getActiveSheet();

            $highestRow = $worksheet->getHighestRow(); // 总行数
            $highestColumn = $worksheet->getHighestColumn(); // 总列数
            $column = 0;
            for ($key = 0; $key < strlen($highestColumn); $key++) {
                $column += $column * 26 + ord($highestColumn[$key]) - ord('A') + 1;
            }
            $highestColumn = $column;
            // 剩余行数
            $rows = $highestRow - $rowStart - $this->offset;
            if ($rows < $this->limit) {
                $this->hasMore = false;
            }
            // 开始读取行
            $start = $rowStart + $this->offset;
            // 下标增加
            $this->offset += $this->limit;
            // 结束行
            $end = min($highestRow, $this->offset + $rowStart);
            $data = [];
            for ($row = $start; $row <= $end; $row++) {
                $l = array();
                for ($key = 0; $key < $highestColumn; $key++) {
                    $value = $worksheet->getCell([$key + $columnStart, $row])->getValue();
                    $value = trim($value);
                    if ($value !== null && $value !== '') {
                        $l[$key] = $value;
                    }
                }
                if ($l) {
                    $data[$row] = $l;
                }
            }
            $result[] = $data;
        }
        return $result;
    }

    /**
     * 更新数据
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-15 18:18:00
     * @param mixed $model
     * @param array $unsetColumn 不可用键
     * @param string $updateKey
     */
    public function updateByModel($model, $unsetColumn = [], $updateKey = 'id')
    {
        foreach ($this->update_lines as $key => $item) {
            try {
                $model->where($updateKey, '=', $item[$updateKey])->update($this->arrayExcept($item, $unsetColumn));
            } catch (\Exception $e) {
                $this->error_lines[] = '第 ' . $key . ' 行 更新失败' . $e->getTraceAsString();
            }
        }
        return true;
    }

    private function arrayExcept($item, $unsetColumn) {
        $res = [];
        foreach($item as $key => $val) {
            if (!in_array($key, $unsetColumn)) {
                $res[$key] = $val;
            }
        }
        return $res;
    }

    /**
     * 更新行指错
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-15 18:59:11
     */
    public function failUpdateLines()
    {
        foreach ($this->update_lines as $key => $item) {
            $this->error_lines[] = '第 ' . $key . ' 行 已存在';
        }
    }

    /**
     * 添加错误信息
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-15 18:59:11
     */
    public function appendError($error)
    {
        $this->error_lines[] = $error;
    }


    /**
     * 是否还有更多数据
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date   2018-08-07 10:36:32
     * @return bool
     */
    public function hasMore()
    {
        return $this->hasMore;
    }

    /**
     * 导出数据
     *
     * @author chentengfei <tengfei.chen@atommatrix.com>
     * @date 2018-10-13 16:30:38
     * @param string $name
     * @param array $data
     * @param array $columns
     * @param int $out
     * @return string|void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(string $name, array $data, array $columns, int $out = self::OUT_STREAM)
    {
        $spreadSheet = new Spreadsheet();
        $workSheet = $spreadSheet->getActiveSheet();
        $workSheet->setTitle($name);
        $i = 1;
        foreach ($columns as $key => $column) {
            $workSheet->setCellValue([$i, 1], $column);
            //设置自动宽度
            // $workSheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
            $row = 2;
            foreach ($data as $item) {
                $value = $item[$key] ?? '';
                $workSheet->setCellValue([$i, $row], str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value));
                $row++;
            }
            $i++;
        }
        if ($out == self::OUT_STREAM) {
            $filename = $name . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
            $writer->save('php://output');
        } else {
            $filename = $name . '.' . strtolower($this->type);
            $writer = IOFactory::createWriter($spreadSheet, $this->type);
            $fullname = STORAGE_PATH . $filename;
            $writer->save($fullname);
            return $fullname;
        }
    }

    public function registerRules(string $ruleName,callable $callable)
    {
        $this->$ruleName = $callable;
    }
}
