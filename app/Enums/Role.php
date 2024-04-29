<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case READER = 'reader';
}
