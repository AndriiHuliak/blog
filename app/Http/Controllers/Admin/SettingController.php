<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::find(1);
        return view('admin.setting.index', compact('setting'));
    }

    public function savedata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_name' => 'required|max:255',
            'logo' => 'nullable',
            'favicon' => 'nullable',
            'description' => 'nullable',
            'meta_title' => 'required|max:255',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
        ]);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator);
        }

        $setting = Setting::where('id', '1')->first();
        if ($setting) 
        {
            $setting->website_name = $request->website_name;

            if ($request->hasfile('logo')) {
                $destination = 'uploads/settings/'.$setting->logo;
                if (File::exists($destination)) 
                {
                    File::delete($destination);
                }

                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/settings/', $filename);
                $setting->logo = $filename;
            }
            if ($request->hasfile('favicon')) {
                $destination = 'uploads/settings/'.$setting->favicon;
                if (File::exists($destination)) 
                {
                    File::delete($destination);
                }

                $file = $request->file('favicon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/settings/', $filename);
                $setting->favicon = $filename;
            }

            $setting->description = $request->description;

            $setting->meta_title = $request->meta_title;
            $setting->meta_description = $request->meta_description;
            $setting->meta_keyword = $request->meta_keyword;
            $setting->save();

            return redirect('admin/settings')->with('message', 'Setting Updated');
        }
        else
        {
            $setting = new Setting;
            $setting->website_name = $request->website_name;

            if ($request->hasfile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/settings/', $filename);
                $setting->logo = $filename;
            }
            if ($request->hasfile('favicon')) {
                $file = $request->file('favicon');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move('uploads/settings/', $filename);
                $setting->favicon = $filename;
            }

            $setting->description = $request->description;

            $setting->meta_title = $request->meta_title;
            $setting->meta_description = $request->meta_description;
            $setting->meta_keyword = $request->meta_keyword;
            $setting->save();

            return redirect('admin/settings')->with('message', 'Setting Added');
        }
    }
}
