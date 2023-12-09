<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluationRealExport implements FromCollection, WithHeadings, WithMapping
{
    private $data;
    public function __construct( $data)
    {
        $this->data = $data;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->customer_id,
            $row->login,
            $row->password,
            $row->type,
            $row->plan_id,
            $row->name,
            $row->comment,
            $row->balance,
            $row->equity,
            $row->credit,
            $row->breached,
            $row->breachedby,
            $row->trading_server_type,
            $row->starting_balance,
            $row->created_at,
            $row->updated_at,
            $row->latestSubscription->ending_at ?? '',
            $row->parentAccount->login ?? "null",
            isset($row->equity) ? (round(($row->equity) - ($row->starting_balance), 6)): '',
            $row->customer->email ?? '',
            $row->country_name ?? '',
        ];

    }

    public function headings(): array
    {
        return [
            'Id',
            'Customer Id',
            'Login',
            'Password',
            'Type',
            'Plan Id',
            'Name',
            'Comment',
            'Balance',
            'Equity',
            'Credit',
            'Breached',
            'Breached By',
            'Trading Server Type',
            'Starting Balance',
            'Created At',
            'Updated At',
            'Ending At',
            'Parent Account',
            'PNL',
            'Email',
            'Country',
        ];
    }
}

