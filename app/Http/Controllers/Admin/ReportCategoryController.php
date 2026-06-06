<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReportCategory\StoreReportCategoryRequest;
use App\Http\Requests\Admin\ReportCategory\UpdateReportCategoryRequest;
use App\Services\Admin\ReportCategoryService;
use Illuminate\Http\Request;

class ReportCategoryController extends Controller
{
    protected ReportCategoryService $reportCategoryService;

    public function __construct(ReportCategoryService $reportCategoryService)
    {
        $this->reportCategoryService = $reportCategoryService;
    }

    public function index()
    {
        $categories = $this->reportCategoryService->getAll();
        return view('admin.report-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.report-categories.create');
    }

    public function store(StoreReportCategoryRequest $request)
    {
        $category = $this->reportCategoryService->create($request->validated());
        return redirect()->route('admin.report-categories.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = $this->reportCategoryService->findById($id);
        if (!$category) {
            return redirect()->route('admin.report-categories.index')->with('error', 'Category not found');
        }
        return view('admin.report-categories.edit', compact('category'));
    }

    public function update(UpdateReportCategoryRequest $request, $id)
    {
        $updated = $this->reportCategoryService->update($id, $request->validated());
        if (!$updated) {
            return redirect()->back()->with('error', 'Category not found');
        }
        return redirect()->route('admin.report-categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $deleted = $this->reportCategoryService->delete($id);
        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }

    public function toggleActive($id)
    {
        $updated = $this->reportCategoryService->toggleActive($id);
        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Category not found'], 404);
        }
        return response()->json(['success' => true, 'message' => 'Category status updated']);
    }
}