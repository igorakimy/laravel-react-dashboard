<?php

namespace App\Data\Integration\ZohoInventory\Account;

use Spatie\LaravelData\Data;

final class AccountData extends Data
{
    public function __construct(
        public string $account_id,
        public string $account_name,
        public string $account_type,
        public string $account_type_formatted,
        public int $account_type_int,
        public bool $allow_multi_currency,
        public string $currency_code,
        public string $currency_id,
        public string $currency_symbol,
        public int $depth,
        public bool $disable_tax,
        public bool $gain_or_loss_account,
        public bool $ignore_currency,
        public bool $is_accounts_payable,
        public bool $is_accounts_receivable,
        public bool $is_active,
        public bool $is_default,
        public bool $is_default_purchase_discount_account,
        public bool $is_disabled_master,
        public bool $is_primary_account,
        public bool $is_retained_earnings,
        public string $masked_account_no,
        public int $price_precision,
        public string $schedule_balancesheet_category,
        public string $schedule_profit_and_loss_category,
    ) {
    }
}
