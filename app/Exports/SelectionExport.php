<?php

namespace App\Exports;

use App\Models\Selection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SelectionExport implements FromView, ShouldAutoSize, WithDrawings, WithStyles
{
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function drawings()
    {
        $path = public_path('images/icon/logoft.png');
        if (! file_exists($path)) {
            return [];
        }

        $drawing = new Drawing;
        $drawing->setName('Logo FT UNSUR');
        $drawing->setDescription('Logo');
        $drawing->setPath($path);
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(20);
        $drawing->setOffsetY(10);

        return $drawing;
    }

    public function view(): View
    {
        $data = Selection::query()
            ->with([
                'application.student',
                'application.scholarship',
                'application.requirementValues.requirement',
                'application.interviews.assessments',
            ])
            ->when($this->filters['search'] ?? null, function ($q, $search) {
                $q->whereHas('application.student', fn ($qs) => $qs
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%")
                );
            })
            ->when($this->filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))
            ->when($this->filters['scholarship_id'] ?? null, function ($q, $id) {
                $q->whereHas('application', fn ($qa) => $qa->where('scholarship_id', $id));
            })
            ->when($this->filters['stage'] ?? null, fn ($q, $s) => $q->where('stage', $s))
            ->latest()
            ->get();

        return view('selections.export-excel', [
            'data' => $data,
            'filters' => $this->filters,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // No specific styles needed here since they are handled in the blade view,
        // but we keep ShouldAutoSize to automatically adjust column widths.
        return [];
    }
}
