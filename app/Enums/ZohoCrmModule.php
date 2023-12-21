<?php

namespace App\Enums;

enum ZohoCrmModule: string
{
    case LEADS = 'Leads';
    case ACCOUNTS = 'Accounts';
    case CONTACTS = 'Contacts';
    case DEALS = 'Deals';
    case CAMPAIGNS = 'Campaigns';
    case TASKS = 'Tasks';
    case CASES = 'Cases';
    case EVENTS = 'Events';
    case CALLS = 'Calls';
    case SOLUTIONS = 'Solutions';
    case PRODUCTS = 'Products';
    case VENDORS = 'Vendors';
    case PRICE_BOOKS = 'Price Books';
    case QUOTES = 'Quotes';
    case SALES_ORDERS = 'Sales Orders';
    case PURCHASE_ORDERS = 'Purchase Orders';
    case INVOICES = 'Invoices';
    case CUSTOM = 'Custom';
    case APPOINTMENTS = 'Appointments';
    case APPOINTMENT_RESCHEDULED_HISTORY = 'Appointment Rescheduled History';
    case SERVICES_AND_ACTIVITIES = 'Services and Activities';
}
