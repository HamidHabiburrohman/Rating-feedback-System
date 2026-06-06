<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Services\Admin\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $group = $request->get('group', 'general');
        $settings = $this->settingService->getByGroup($group);
        $groups = $this->settingService->getAll()->groupBy('group')->keys();
        
        return view('admin.settings.index', compact('settings', 'groups', 'group'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $updated = $this->settingService->updateMultiple($request->settings);
        if (!$updated) {
            return redirect()->back()->with('error', 'Failed to update settings');
        }
        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function get($key)
    {
        $value = $this->settingService->get($key);
        return response()->json(['success' => true, 'data' => $value]);
    }
}