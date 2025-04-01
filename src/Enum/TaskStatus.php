<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = "Todo";
    case DOING = "Doing";
    case DONE = "Done";
}
