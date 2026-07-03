<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function edit(): View
    {
        $setting = WebsiteSetting::current();

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'admin_whatsapp' => ['required', 'string', 'max:30'],
        ]);

        WebsiteSetting::current()->update([
            'admin_whatsapp' => preg_replace('/[^0-9]/', '', $validated['admin_whatsapp']),
        ]);

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
