<?php

namespace App\Exports;

use App\Models\BlogCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BlogCategoryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BlogCategory::all()
            ->map(function ($category ,$index) {
                return [
                    'iteration' => $index + 1,
                    'name' => $category->category_name,
                    'created_at' => $category->created_at->format('d-m-Y'),
                ];
            });
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Category Name',
            'Created At',
        ];
    }
}
