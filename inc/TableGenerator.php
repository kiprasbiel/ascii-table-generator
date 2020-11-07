<?php
namespace inc;

class TableGenerator
{
    private $data;
    private $columnsNames = [];
    private $columnsLength = [];
    private $finalTable = [];

    public function __construct($array) {
        $this->data = $array;
    }

    private function findColumns()
    {
        $this->columnsNames = array_keys(reset($this->data));
    }

    private function calcColumnsLength()
    {
        foreach ($this->data as $row) {
            foreach($row as $key => $item){
                $this->columnsLength[$key] = max(
                    isset($this->columnsLength[$key]) ? $this->columnsLength[$key] : 0,
                    strlen($key),
                    strlen($item)
                );
            }
        }
    }

    private function lineSeparator(){
        $string = '';

        foreach($this->columnsLength as $length){
            $string .= '+' . str_repeat('-', $length + 2) . '+';
        }

        $this->finalTable[] = $string;
    }

    private function tableHeader(){
        $string = '';

        foreach($this->columnsLength as $key => $totalLen){
            $string .= $this->tableColumn($key, $totalLen);
        }

        $this->finalTable[] = $string;
    }

    private function tableColumn($item, $length){
        return '| ' . $item . str_repeat(' ', $length + 1 - strlen($item)) . '|';
    }

    private function tableBody(){
        foreach($this->data as $row){
            $strRow = '';
            foreach($this->columnsNames as $name){
                $strRow .= $this->tableColumn($row[$name], $this->columnsLength[$name]);
            }
            $this->finalTable[] = $strRow;
        }
    }

    private function finalPreparations(){
        $tableStr = implode(PHP_EOL, $this->finalTable);
        return str_replace(['++', '||'], ['+', '|'], $tableStr);
    }

    public function make(){
        $this->findColumns();
        $this->calcColumnsLength();

        $this->lineSeparator();
        $this->tableHeader();
        $this->lineSeparator();

        $this->tableBody();
        $this->lineSeparator();

        return $this->finalPreparations();
    }
}