<?php

namespace App\Enums;

enum Permission: string
{
    // Users
    case CAN_READ_USER = 'users.show';
    case CAN_CREATE_USER = 'users.store';
    case CAN_UPDATE_USER = 'users.update';
    case CAN_DELETE_USER = 'users.destroy';
    case CAN_READ_LIST_USERS = 'users.index';

    // Roles
    case CAN_READ_ROLE = 'roles.show';
    case CAN_CREATE_ROLE = 'roles.store';
    case CAN_UPDATE_ROLE = 'roles.update';
    case CAN_DELETE_ROLE = 'roles.destroy';
    case CAN_READ_LIST_ROLES = 'roles.index';

    // Permissions
    case CAN_READ_PERMISSION = 'permissions.show';
    case CAN_CREATE_PERMISSION = 'permissions.store';
    case CAN_UPDATE_PERMISSION = 'permissions.update';
    case CAN_DELETE_PERMISSION = 'permissions.destroy';
    case CAN_READ_LIST_PERMISSIONS = 'permissions.index';

    // Invitations
    case CAN_SEND_INVITATION = 'invitations.send';
    case CAN_RESEND_INVITATION = 'invitations.resend';
    case CAN_REVOKE_INVITATION = 'invitations.revoke';
    case CAN_READ_LIST_INVITATIONS = 'invitations.index';

    // Categories
    case CAN_READ_CATEGORY = 'categories.show';
    case CAN_CREATE_CATEGORY = 'categories.store';
    case CAN_UPDATE_CATEGORY = 'categories.update';
    case CAN_DELETE_CATEGORY = 'categories.destroy';
    case CAN_READ_LIST_CATEGORIES = 'categories.index';

    // Products
    case CAN_READ_PRODUCT = 'products.show';
    case CAN_CREATE_PRODUCT = 'products.store';
    case CAN_UPDATE_PRODUCT = 'products.update';
    case CAN_DELETE_PRODUCT = 'products.destroy';
    case CAN_READ_LIST_PRODUCTS = 'products.index';
    case CAN_UPLOAD_MEDIA = 'products.upload_media';
    case CAN_DELETE_MEDIA = 'products.delete_media';

    // Settings
    case CAN_READ_LIST_SETTINGS = 'settings.index';
    case CAN_READ_ZOHO_BOOKS_SETTINGS = 'settings.zoho_books.index';
    case CAN_UPDATE_ZOHO_BOOKS_SETTINGS = 'settings.zoho_books.update';

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

            self::CAN_SEND_INVITATION => 'Send Invitation',
            self::CAN_RESEND_INVITATION => 'Resend Invitation',
            self::CAN_REVOKE_INVITATION => 'Revoke Invitation',
            self::CAN_READ_LIST_INVITATIONS => 'Read All Invitations',

            self::CAN_READ_CATEGORY => 'Read Category',
            self::CAN_CREATE_CATEGORY => 'Create Category',
            self::CAN_UPDATE_CATEGORY => 'Update Category',
            self::CAN_DELETE_CATEGORY => 'Delete Category',
            self::CAN_READ_LIST_CATEGORIES => 'Read All Categories',

            self::CAN_READ_PRODUCT => 'Read Product',
            self::CAN_CREATE_PRODUCT => 'Create Product',
            self::CAN_UPDATE_PRODUCT => 'Update Product',
            self::CAN_DELETE_PRODUCT => 'Delete Product',
            self::CAN_READ_LIST_PRODUCTS => 'Read All Products',
            self::CAN_UPLOAD_MEDIA => 'Upload Product Media',
            self::CAN_DELETE_MEDIA => 'Delete Product Media',

            self::CAN_READ_LIST_SETTINGS => 'Read All Settings',
            self::CAN_READ_ZOHO_BOOKS_SETTINGS => 'Read Zoho Books Settings',
            self::CAN_UPDATE_ZOHO_BOOKS_SETTINGS => 'Update Zoho Books Settings',
        };
    }
}
