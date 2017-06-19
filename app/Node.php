<?php

namespace App;

class Node
{
    public $id;

    public $code;

    public $text;

    public $state;

    public $nodes;

    public function __construct($id = null, $code = null, $text = null,$state = null)
    {
        $this->id = $id;
        $this->code = $code;
        $this->text = $text;
    }
}