<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\CatMoodService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected CatMoodService $catMoodService;

    public function __construct(CatMoodService $catMoodService)
    {
        $this->catMoodService = $catMoodService;
    }

    public function index()
    {
        $employees = Employee::orderBy('is_active', 'desc')
            ->orderBy('employment_type')
            ->get();

        $stats = [
            'total' => $employees->count(),
            'fulltime' => $employees->where('employment_type', 'FULLTIME')->count(),
            'freelance' => $employees->where('employment_type', 'FREELANCE')->count(),
            'retainer' => $employees->where('employment_type', 'RETAINER')->count(),
        ];

        $catMood = $this->catMoodService->determineEmployeeMood($employees);

        return view('employees.index', compact('employees', 'stats', 'catMood'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'employment_type' => 'required|in:FULLTIME,FREELANCE,RETAINER',
            'base_salary_or_rate' => 'required|numeric|min:0',
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'location' => 'required|string|max:50',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Pekerja/Talent baru berhasil ditambahkan! 🐾')
            ->with('action', 'create');
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'employment_type' => 'required|in:FULLTIME,FREELANCE,RETAINER',
            'base_salary_or_rate' => 'required|numeric|min:0',
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'location' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Data pekerja berhasil diperbarui! ✨')
            ->with('action', 'update');
    }

    public function destroy(Employee $employee)
    {
        $name = $employee->name;
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', "Data pekerja {$name} berhasil dihapus.")
            ->with('action', 'delete');
    }
}
