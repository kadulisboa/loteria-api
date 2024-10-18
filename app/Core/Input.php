<?php

namespace App\Core;

class Input
{
    public function get(): string
    {
        return file_get_contents('php://input');
    }
}