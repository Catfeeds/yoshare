<?php
namespace App\Libraries\wePay\lib;

interface ILogHandler
{
    public function write($msg);
}