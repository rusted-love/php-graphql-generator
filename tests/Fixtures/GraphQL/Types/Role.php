<?php
declare(strict_types=1);

enum Role: string
{
    case Admin = 'ROLE_ADMIN';
    case Manager = 'Manager';
    case Developer = 'Developer';
}
