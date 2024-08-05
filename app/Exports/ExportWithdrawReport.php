<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportWithdrawReport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $date = null;
    private $report_type = null;
    public function __construct($report_type, $date)
    {
        $this->report_type = $report_type;
        $this->date = $date;
    }

    public function collection()
    {
         $query = Transaction::query()
            ->from('transactions as t')
            ->select(
                't.id',
                'users.name',
                'users.upi',
                'users.ifsc',
                'users.bank',
                'users.phone',
                't.transaction_date as original_date',
                't.points',
                't.transaction_type',
            )
            ->join('users', 't.user_id', 'users.id')
            ->orderBy('t.id', 'DESC');
         if ($this->report_type == 'deposit') {
             $query->where('t.transaction_type', 'deposit');
         } else {
             $query->where('t.transaction_type', 'withdraw')
                 ->where('t.transaction_creator', 'user')
                 ->where('t.status', 'pending');
         }
         if ($this->date) {
             $query->whereDate('transaction_date', $this->date);
         }

         return $query->get();
    }
    public function headings(): array
    {
        return [
            'ID',
            'NAME',
            'UPI',
            'IFSC',
            'BANK',
            'PHONE',
            'ORIGINAL DATE',
            'POINTS',
            'STATUS',
        ];
    }
}
