<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Symfony\Component\Intl\Currencies;
use Symfony\Component\Intl\Locales;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'name');

        return view('dashboard.settings', [
            'currencies' => Currencies::getNames(),
            'locales' => Locales::getNames(),
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings.app_name' => 'required',
            'settings.app_currency' => ['required', 'string', 'size:3'],
            'settings.app_locale' => ['required', 'string', 'min:2', 'max:5'],
        ]);

        foreach ($request->post('settings') as $key => $value) {
            Setting::updateOrCreate([
                'name' => str_replace('_', '.', $key),
            ], [
                'value' => $value,
            ]);
        }
        
        event('settings.updated');

        return redirect()->route('dashboard.settings.edit')
            ->with('success', 'Settings saved.');
    }
}
