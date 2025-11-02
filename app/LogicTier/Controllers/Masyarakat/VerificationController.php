<?php
namespace App\LogicTier\Controllers\Masyarakat;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\DataTier\Models\User;

class VerificationController extends BaseController
{
    public function showVerifyForm()
    {
        return view('presentation_tier.masyarakat.auth.verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ]);

        $user = User::where('verification_code', $request->code)->first();

        if ($user) {
            $user->is_verified = true;
            $user->verification_code = null;
            $user->email_verified_at = now();
            $user->save();

            // langsung ke login
            return redirect('/login')->with('success', 'Verifikasi berhasil! Silakan login.');
        }

        return back()->with('error', 'Kode verifikasi salah.');
    }

    public function resendCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && !$user->is_verified) {
            $newCode = rand(100000, 999999);
            $user->verification_code = $newCode;
            $user->save();

            \Mail::raw("Kode verifikasi baru Anda adalah: $newCode", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Kode Verifikasi Baru');
            });

            return back()->with('success', 'Kode verifikasi baru sudah dikirim ke email.');
        }

        return back()->with('error', 'Email tidak ditemukan atau sudah terverifikasi.');
    }
}
