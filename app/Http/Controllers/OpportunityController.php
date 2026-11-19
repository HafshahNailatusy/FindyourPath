<?php

namespace App\Http\Controllers;

use App\Models\CareerPlan;
use App\Models\StudentAchievement;
use App\Models\StudentGoal;
use App\Models\StudentProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OpportunityController extends Controller
{
    private array $models = [
        'goals'        => StudentGoal::class,
        'projects'     => StudentProject::class,
        'achievements' => StudentAchievement::class,
        'career'       => CareerPlan::class,
    ];

    private function authorizeStudent(): int
    {
        $user = Auth::user();
        $role = strtolower($user->role ?? '');

        if (!$user || !in_array($role, ['mahasiswa', 'user'], true)) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return (int) $user->id;
    }

    public function index()
    {
        $userId = $this->authorizeStudent();

        $goals = StudentGoal::where('user_id', $userId)->orderByDesc('id')->get();
        $projects = StudentProject::where('user_id', $userId)->orderByDesc('id')->get();
        $achievements = StudentAchievement::where('user_id', $userId)->orderByDesc('id')->get();
        $careerPlans = CareerPlan::where('user_id', $userId)->orderByDesc('id')->get();

        $formatRecords = function ($collection, string $fallbackCategory) {
            return $collection->map(function ($item) use ($fallbackCategory) {
                $arr = $item->toArray();
                $arr['display_title'] = $arr['title'] ?? $arr['project_name'] ?? $arr['career_title'] ?? '';
                $arr['display_category'] = $arr['category'] ?? $fallbackCategory;
                $arr['display_date'] = $arr['target_date'] ?? $arr['end_date'] ?? $arr['achievement_date'] ?? null;
                $arr['has_status'] = array_key_exists('status', $arr) && $arr['status'] !== null;
                $arr['status_class'] = $arr['status'] ?? 'completed';
                $arr['status_label'] = Str::headline($arr['status'] ?? 'completed');
                $arr['json_payload'] = json_encode($item->toArray());
                return $arr;
            });
        };

        $sections = [
            'goals' => [
                'title'    => 'My Goals & Skills',
                'subtitle' => 'Catat target dan kemampuan yang ingin kamu kembangkan.',
                'records'  => $formatRecords($goals, 'My Goals & Skills'),
            ],
            'projects' => [
                'title'    => 'My Projects',
                'subtitle' => 'Dokumentasikan proyek dan karya yang pernah kamu buat.',
                'records'  => $formatRecords($projects, 'My Projects'),
            ],
            'achievements' => [
                'title'    => 'My Achievements',
                'subtitle' => 'Simpan pencapaian dan pengalaman berhargamu.',
                'records'  => $formatRecords($achievements, 'My Achievements'),
            ],
            'career' => [
                'title'    => 'Career Roadmap',
                'subtitle' => 'Rencanakan langkah menuju karier impianmu.',
                'records'  => $formatRecords($careerPlans, 'Career Roadmap'),
            ],
        ];

        $goalsCount = $goals->count();
        $projectsCount = $projects->count();
        $achievementsCount = $achievements->count();
        $totalRecords = $goalsCount + $projectsCount + $achievementsCount + $careerPlans->count();

        $completedGoals = $goals->filter(
            fn($item) => ($item->status ?? '') === 'completed' || (int) ($item->progress ?? 0) >= 100
        )->count();

        $completedProjects = $projects->where('status', 'completed')->count();
        $completedItems = $completedGoals + $completedProjects;

        $userName = Auth::user()->name ?? 'Mahasiswa';
        $userInitial = strtoupper(substr($userName, 0, 1));

        return view('opportunity.findOpportunities', compact(
            'sections',
            'goalsCount',
            'projectsCount',
            'achievementsCount',
            'totalRecords',
            'completedItems',
            'userName',
            'userInitial'
        ));
    }

    public function handleRequest(Request $request)
    {
        $userId = $this->authorizeStudent();

        $action = $request->input('action');
        $type = $request->input('type');
        $id = (int) $request->input('id', 0);

        if (!isset($this->models[$type])) {
            abort(422, 'Jenis data tidak valid.');
        }

        $modelClass = $this->models[$type];

        if ($action === 'delete') {
            if ($id <= 0) {
                abort(422, 'ID tidak valid.');
            }

            $modelClass::where('id', $id)->where('user_id', $userId)->delete();
            return redirect()->route('opportunity.index');
        }

        if (!in_array($action, ['create', 'update'], true)) {
            abort(422, 'Aksi tidak valid.');
        }

        $rulesByType = [
            'goals' => [
                'title'         => 'required|string|max:255',
                'category'      => 'required|in:Goals,Skills',
                'current_level' => 'nullable|string|max:100',
                'target_level'  => 'nullable|string|max:100',
                'progress'      => 'nullable|integer|min:0|max:100',
                'target_date'   => 'nullable|date',
                'description'   => 'nullable|string',
            ],
            'projects' => [
                'project_name' => 'required|string|max:255',
                'category'     => 'nullable|string|max:100',
                'technologies' => 'nullable|string|max:255',
                'project_url'  => 'nullable|string|max:255',
                'description'  => 'nullable|string',
                'status'       => 'nullable|in:planned,in_progress,completed',
            ],
            'achievements' => [
                'title'            => 'required|string|max:255',
                'category'         => 'nullable|string|max:100',
                'organizer'        => 'nullable|string|max:255',
                'achievement_date' => 'nullable|date',
                'description'      => 'nullable|string',
                'certificate_file' => 'nullable|string|max:255',
            ],
            'career' => [
                'career_title' => 'required|string|max:255',
                'description'  => 'nullable|string',
                'target_date'  => 'nullable|date',
                'status'       => 'nullable|in:planned,in_progress,completed',
            ],
        ];

        $validated = $request->validate($rulesByType[$type]);

        if ($action === 'create') {
            $validated['user_id'] = $userId;
            $modelClass::create($validated);
        } else {
            if ($id <= 0) {
                abort(422, 'ID tidak valid.');
            }

            $record = $modelClass::where('id', $id)->where('user_id', $userId)->firstOrFail();
            $record->update($validated);
        }

        return redirect()->route('opportunity.index');
    }
}