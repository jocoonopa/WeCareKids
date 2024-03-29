<?php

namespace App\Utility\Services\AmtCell;

use App\Model\AmtDiagStandard;

trait ParserTrait
{
    public function convertToStatment()
    {
        return $this
            ->_filterNonValidAlphabet() // 先過濾白名單以外的英文字
            ->_filterExceptAlphabetAndNumber() // 過濾小寫英文和數字以外的東西
            ->_convertLogicExpression() // 邏輯文字轉換為邏輯符號
            ->_convertExecuteCode() // 將數字轉換為特殊程式碼
            ->str
        ;
    }

    public function getIds()
    {
        preg_match_all('/\d+/', $this->str, $matches[0]);
        
        return array_first($matches[0]);
    }

    public function getStandards()
    {
        return AmtDiagStandard::find($this->getIds());
    }

    protected function isValidAlphabet($char)
    {
        return in_array($char, static::$whiteAlphabets);
    }

    protected function isValidChar($char)
    {
        return in_array($char, static::$whiteChars);
    }

    private function _filterNonValidAlphabet()
    {
        return $this->setStr(preg_replace_callback('/[A-Za-z]/', function($m) {
            return $this->isValidAlphabet($m[0]) ? $m[0] : '';
        }, $this->str));
    }

    private function _filterExceptAlphabetAndNumber()
    {
        return $this->setStr(preg_replace_callback('#[^a-z0-9]#', function($m) {
            return $this->isValidChar($m[0]) ? $m[0] : '';
        }, $this->str));
    }

    private function _convertLogicExpression()
    {
        return $this->setStr(str_replace(['and', 'or'], [' && ', ' || '], $this->str));
    }

    private function _convertExecuteCode()
    {
        return $this->setStr(preg_replace_callback('/\d+/', function($m) {
            return '\App\Model\AmtDiagStandard::find(' . $m[0] .')->isPassWithMacthed($replicaDiags)';
        }, $this->str));
    }

    /**
     * Sets the value of str.
     *
     * @param mixed $str the str
     *
     * @return self
     */
    public function setStr($str)
    {
        $this->str = $str;

        return $this;
    }
}