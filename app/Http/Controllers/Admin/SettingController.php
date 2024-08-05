<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\NoticeBoard;
use App\Models\RatioSetting;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\WithdrawalSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function ratioSetting() {
        $setting = RatioSetting::where('market_type', 'other')->first();
        return view('admin.pages.settings.ratio-settings', compact('setting'));
    }
    public function ratioSettingUpdate(Request $request) {
        try {
            $setting = RatioSetting::where('market_type', 'other')->first();
            if (empty($setting)) {
                $setting = new Setting();
            }
            $setting->single_digit = $request->single_digit;
            $setting->double_digit = $request->double_digit;
            $setting->single_pana = $request->single_pana;
            $setting->double_pana = $request->double_pana;
            $setting->tripple_pana = $request->tripple_pana;
            $setting->half_sangum = $request->half_sangum;
            $setting->full_sangum = $request->full_sangum;
            $setting->save();
            if ($setting) {
                $output = ['success' => true, 'msg' => 'Setting Updated!'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('setting.ratio')->with('status', $output);
    }
    public function starSetting() {
        $setting = RatioSetting::where('market_type', 'starline')->first();
        return view('admin.pages.settings.star-settings', compact('setting'));
    }
    public function starSettingUpdate(Request $request) {
        try {
            $setting = RatioSetting::where('market_type', 'starline')->first();
            if (empty($setting)) {
                $setting = new RatioSetting();
            }
            $setting->single_digit = $request->single_digit;
            $setting->single_pana = $request->single_pana;
            $setting->double_pana = $request->double_pana;
            $setting->tripple_pana = $request->tripple_pana;
            $setting->save();
            if ($setting) {
                $output = ['success' => true, 'msg' => 'Setting Updated!'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('setting.star')->with('status', $output);
    }
    public function delhiSetting() {
        $setting = RatioSetting::where('market_type', 'delhi')->first();
        return view('admin.pages.settings.delhi-settings', compact('setting'));
    }
    public function delhiSettingUpdate(Request $request) {
        try {
            $setting = RatioSetting::where('market_type', 'delhi')->first();
            if (empty($setting)) {
                $setting = new RatioSetting();
            }
            $setting->ander = $request->ander;
            $setting->baher = $request->baher;
            $setting->jodi = $request->jodi;
            $setting->save();
            if ($setting) {
                $output = ['success' => true, 'msg' => 'Setting Updated!'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('setting.delhi')->with('status', $output);
    }
    public function otherSetting() {
        $setting = Setting::first();
        return view('admin.pages.settings.other-settings', compact('setting'));
    }
    public function otherSettingUpdate(Request $request) {
        try {
            $isUpi = ($request->isUpi) ? true : false;
            $isUMoney = ($request->isUMoney) ? true : false;
            $isPhonePe = ($request->isPhonePe) ? true : false;
            $setting = Setting::first();
            $setting->vpa = $request->vpa;
            $setting->name = $request->name;
            $setting->merchant_key = $request->merchant_key;
            $setting->salt_key = $request->salt_key;
            $setting->guessing = $request->guessing;
            $setting->results_and_videos = $request->results_and_videos;
            $setting->whatsapp_channel = $request->whatsapp_channel;
            $setting->website = $request->website;
            $setting->phone = $request->phone;
            $setting->whatsapp = $request->whatsapp;
            $setting->phonepe_key = $request->phonepe_key;
            $setting->isUpi = $isUpi;
            $setting->isUMoney = $isUMoney;
            $setting->isPhonePe = $isPhonePe;
            $setting->razorpay_key_id = $request->razorpay_key_id;
            $setting->razorpay_secret_key = $request->razorpay_secret_key;
            $setting->withdraw_limit = $request->withdraw_limit;
            $setting->save();
            if ($setting) {
                $output = ['success' => true, 'msg' => 'Setting Updated!'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('setting.other')->with('status', $output);
    }
    public function sliderSetting($type) {
        $slides = Slide::where('type', $type)->get();
        return view('admin.pages.settings.slider-settings', compact('slides', 'type'));
    }
    public function storeSlides(Request $request)
    {
        $request->validate([
            'slides.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Define your image validation rules
        ]);

        foreach ($request->file('slides') as $file) {
            // Store the uploaded slide images and save their paths in the database
            $imagePath = $file->store('slides', 'public');
            Slide::create([
                'title' => $file->getClientOriginalName(),
                'image_path' => $imagePath,
                'type' => $request->type,
            ]);
        }
        $output = ['success' => true, 'msg' => 'Slides uploaded successfully.'];
        return redirect()->back()->with('status', $output);
    }
    public function destroySlides($type, $id)
    {
        $slide = Slide::findOrFail($id);
        // Delete the image file from storage if needed
         Storage::disk('public')->delete($slide->image_path);
        $slide->delete();
        $output = ['success' => true, 'msg' => 'Slide deleted successfully.'];
        return redirect()->route('setting.slider', ['type' => $type])->with('status', $output);
    }
    public function withdrawalSettings() {
        $withdrawal_settings = WithdrawalSetting::first();
        if (isset($withdrawal_settings->days_of_week)) {
            $savedDays = explode(',', $withdrawal_settings->days_of_week);
        }
        else {
            $savedDays = [];
        }
        return view('admin.pages.settings.withdrawal-settings', compact('withdrawal_settings', 'savedDays'));
    }
    public function updateWithdrawalSettings(Request $request)
    {
        try {
            $data = $request->validate([
                'days_of_week' => 'nullable',
                'start_time' => 'required|date_format:g:i A',
                'end_time' => 'required',
            ]);
            if (isset($data['days_of_week'])) {
                $data['days_of_week'] = implode(',', $data['days_of_week']);
            } else {
                $data['days_of_week'] = '';
            }


            $data['start_time'] = Carbon::parse($data['start_time'])->format('H:i:s');
            $data['end_time'] = Carbon::parse($data['end_time'])->format('H:i:s');

            WithdrawalSetting::truncate();
            $setting = WithdrawalSetting::create($data);

            if ($setting) {
                $output = ['success' => true, 'msg' => 'Withdrawal settings updated successfully.'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }

        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }

        return redirect()->route('withdrawal-settings')->with('status', $output);
    }

    public function noticeBoard($type) {
        $noticeBoard = NoticeBoard::where('market_type', $type)->first();
        return view('admin.pages.settings.notice-board', compact('noticeBoard','type'));
    }
    public function updateNoticeBoard(Request $request) {
        try {
            $data = $request->validate([
                'title' => 'required',
                'content' => 'required',
                'market_type' => 'required',
            ]);

            $noticeBoard = NoticeBoard::where('market_type', $data['market_type'])->first();
            if ($noticeBoard) {
                $noticeBoard->update($data);
            } else {
                $noticeBoard = NoticeBoard::create($data);
            }

            if ($noticeBoard) {
                $output = ['success' => true, 'msg' => 'Notice Board Updated Successfully.'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->back()->with('status', $output);
    }

}
