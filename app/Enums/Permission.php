<?php

namespace App\Enums;

enum Permission: string
{
    case CAN_READ_USER = 'users.show';
    case CAN_CREATE_USER = 'users.store';
    case CAN_UPDATE_USER = 'users.update';
    case CAN_DELETE_USER = 'users.destroy';
    case CAN_READ_LIST_USERS = 'users.index';

    case CAN_READ_ROLE = 'roles.show';
    case CAN_CREATE_ROLE = 'roles.store';
    case CAN_UPDATE_ROLE = 'roles.update';
    case CAN_DELETE_ROLE = 'roles.destroy';
    case CAN_READ_LIST_ROLES = 'roles.index';

    case CAN_READ_PERMISSION = 'permissions.show';
    case CAN_CREATE_PERMISSION = 'permissions.store';
    case CAN_UPDATE_PERMISSION = 'permissions.update';
    case CAN_DELETE_PERMISSION = 'permissions.destroy';
    case CAN_READ_LIST_PERMISSIONS = 'permissions.index';

    /**
     * Get the name of the permission.
     *
     * @return string
     */
    public function name(): string
    {
        return match ($this) {
            self::CAN_READ_USER => 'Read User',
            self::CAN_CREATE_USER => 'Create User',
            self::CAN_UPDATE_USER => 'Update User',
            self::CAN_DELETE_USER => 'Delete User',
            self::CAN_READ_LIST_USERS => 'Read All Users',

            self::CAN_READ_ROLE => 'Read Role',
            self::CAN_CREATE_ROLE => 'Create Role',
            self::CAN_UPDATE_ROLE => 'Update Role',
            self::CAN_DELETE_ROLE => 'Delete Role',
            self::CAN_READ_LIST_ROLES => 'Read All Roles',

            self::CAN_READ_PERMISSION => 'Read Permission',
            self::CAN_CREATE_PERMISSION => 'Create Permission',
            self::CAN_UPDATE_PERMISSION => 'Update Permission',
            self::CAN_DELETE_PERMISSION => 'Delete Permission',
            self::CAN_READ_LIST_PERMISSIONS => 'Read All Permissions',
        };
    }
}


