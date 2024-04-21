<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportItemParams implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Item::searchQuery()
            ->sortingQuery()
            ->filterQuery()
            ->filterDateQuery();
        if (!empty($this->filters)) {
        }

        return $query->select('id', 'name', 'open_time', 'close_time', 'address', 'phone', 'created_by', 'updated_by', 'created_at', 'updated_at')->get();
    }

    public function headings(): array
    {
        return [
            'Id',
            'Name',
            'Open Time',
            'Close Time',
            'Address',
            'Phone',
            'Created By',
            'Updated By',
            'Created At',
            'Updated At',
        ];
    }

    public function map($post): array
    {
        $createdByUser = User::find($post->created_by);
        $updatedByUser = User::find($post->updated_by);

        return [
            $post->id,
            $post->name,
            $post->open_time,
            $post->close_time,
            $post->address,
            $post->phone,
            $createdByUser ? $createdByUser->name : 'Unknown',
            $updatedByUser ? $updatedByUser->name : 'Unknown',
            $post->created_at,
            $post->updated_at
        ];
    }
}