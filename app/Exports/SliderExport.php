<?php

namespace App\Exports;

use App\Models\Slider;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SliderExport implements FromCollection, WithHeadings
{
    /**
     * Prepare data for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Slider::all()->map(function ($slider, $key) {
            return [
                'index' => $key + 1,
                'position' => $slider->position,
                'status' => $slider->status == 1 ? 'Active' : 'Inactive',
                'start_end_dates' => ($slider->start_date && $slider->end_date)
                    ? Carbon::parse($slider->start_date)->format('Y-m-d') . ' to ' . Carbon::parse($slider->end_date)->format('Y-m-d')
                    : 'null',
                'created_at' => $slider->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $slider->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    /**
     * Add headings to the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Sr.NO',
            'Position',
            'Status',
            'Scheduled now',
            'Created At',
            'Updated At',
        ];
    }
}
