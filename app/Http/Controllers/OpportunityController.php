<?php

namespace App\Http\Controllers;

use App\Models\CareerPlan;
use App\Models\StudentAchievement;
use App\Models\StudentProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;


class OpportunityController extends Controller
{
    private const LEVELS = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];

    private const RELATIONS = [
        'goals' => 'goals',
        'projects' => 'projects',
        'achievements' => 'achievements',
        'career' => 'careerPlans',
    ];

    private const SUCCESS_LABEL = [
        'goals' => 'Goal / skill',
        'projects' => 'Proyek',
        'achievements' => 'Pencapaian',
        'career' => 'Rencana karier',
    ];

    public function index(Request $request): View
    {
        $user = $request->user();

        return view('opportunity.index', [
            'goals' => $user->goals,
            'projects' => $user->projects,
            'achievements' => $user->achievements,
            'careerPlans' => $user->careerPlans,
            'levels' => self::LEVELS,
            'projectStatuses' => StudentProject::STATUSES,
            'careerStatuses' => CareerPlan::STATUSES,
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $validator = $this->validator($request, $type);

        if ($validator->fails()) {
            return $this->invalid($validator, $type);
        }

        $data = $this->prepare($request, $type, $validator->validated());

        $request->user()->{self::RELATIONS[$type]}()->create($data);

        return $this->done($type, self::SUCCESS_LABEL[$type].' berhasil ditambahkan.');
    }

    public function update(Request $request, string $type, int $id): RedirectResponse
    {
        $record = $request->user()->{self::RELATIONS[$type]}()->findOrFail($id);

        $validator = $this->validator($request, $type);

        if ($validator->fails()) {
            return $this->invalid($validator, $type, $id);
        }

        $data = $this->prepare($request, $type, $validator->validated(), $record);

        $record->update($data);

        return $this->done($type, self::SUCCESS_LABEL[$type].' berhasil diperbarui.');
    }

    public function destroy(Request $request, string $type, int $id): RedirectResponse
    {
        $record = $request->user()->{self::RELATIONS[$type]}()->findOrFail($id);

        if ($record instanceof StudentAchievement && $record->certificate_file) {
            Storage::disk('public')->delete($record->certificate_file);
        }

        $record->delete();

        return $this->done($type, self::SUCCESS_LABEL[$type].' berhasil dihapus.');
    }

    private function rules(string $type): array
    {
        return match ($type) {
            'goals' => [
                'title' => ['required', 'string', 'max:150'],
                'category' => ['nullable', 'string', 'max:50'],
                'current_level' => ['nullable', Rule::in(self::LEVELS)],
                'target_level' => ['nullable', Rule::in(self::LEVELS)],
                'progress' => ['nullable', 'integer', 'between:0,100'],
                'target_date' => ['nullable', 'date'],
                'description' => ['nullable', 'string', 'max:5000'],
            ],
            'projects' => [
                'project_name' => ['required', 'string', 'max:150'],
                'category' => ['nullable', 'string', 'max:50'],
                'technologies' => ['nullable', 'string', 'max:1000'],
                'project_url' => ['nullable', 'url', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'status' => ['required', Rule::in(StudentProject::STATUSES)],
            ],
            'achievements' => [
                'title' => ['required', 'string', 'max:150'],
                'category' => ['nullable', 'string', 'max:50'],
                'organizer' => ['nullable', 'string', 'max:150'],
                'achievement_date' => ['nullable', 'date'],
                'description' => ['nullable', 'string', 'max:5000'],
                'certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            ],
            'career' => [
                'career_title' => ['required', 'string', 'max:150'],
                'description' => ['nullable', 'string', 'max:5000'],
                'target_date' => ['nullable', 'date'],
                'status' => ['required', Rule::in(CareerPlan::STATUSES)],
            ],
        };
    }

    private function validator(Request $request, string $type)
    {
        return Validator::make($request->all(), $this->rules($type), [
            'required' => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => ':attribute maksimal :max KB.',
            'url' => ':attribute harus berupa URL yang valid (diawali http:// atau https://).',
            'date' => ':attribute bukan tanggal yang valid.',
            'integer' => ':attribute harus berupa angka.',
            'between.numeric' => ':attribute harus antara :min sampai :max.',
            'in' => 'Pilihan :attribute tidak valid.',
            'mimes' => ':attribute harus berupa file PDF, JPG, atau PNG.',
            'file' => ':attribute harus berupa file.',
        ], [
            'title' => 'Judul',
            'project_name' => 'Nama proyek',
            'career_title' => 'Karier impian',
            'category' => 'Kategori',
            'current_level' => 'Level saat ini',
            'target_level' => 'Level target',
            'progress' => 'Progress',
            'target_date' => 'Target tanggal',
            'description' => 'Deskripsi',
            'technologies' => 'Teknologi',
            'project_url' => 'Link proyek',
            'status' => 'Status',
            'organizer' => 'Penyelenggara',
            'achievement_date' => 'Tanggal pencapaian',
            'certificate_file' => 'File sertifikat',
        ]);
    }

    private function prepare(Request $request, string $type, array $data, $record = null): array
    {
        if ($type === 'goals') {
            $data['progress'] = $data['progress'] ?? 0;
        }

        if ($type === 'achievements') {
            unset($data['certificate_file']);

            if ($request->hasFile('certificate_file')) {
                if ($record?->certificate_file) {
                    Storage::disk('public')->delete($record->certificate_file);
                }

                $data['certificate_file'] = $request->file('certificate_file')
                    ->store('certificates', 'public');
            }
        }

        return $data;
    }

    private function invalid($validator, string $type, ?int $id = null): RedirectResponse
    {
        return redirect()->route('opportunities.index')
            ->withErrors($validator)
            ->withInput()
            ->with('tab', $type)
            ->with('reopen', ['type' => $type, 'id' => $id]);
    }

    private function done(string $type, string $message): RedirectResponse
    {
        return redirect()->route('opportunities.index')
            ->with('success', $message)
            ->with('tab', $type);
    }
}
