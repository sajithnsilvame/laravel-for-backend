<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Superadmin = 'superadmin';
    case Admin = 'admin';
    case User = 'user';
}
