<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('My Profile')]
class ProfileIndex extends Component
{
    public $step = 'overview'; // 'overview' or 'enroll'

    public function enrollFace($frontData, $leftData = null, $rightData = null)
    {
        try {
            if (empty($frontData)) {
                throw new \Exception('Gambar profil wajah tampak depan wajib diisi.');
            }

            // Save front face (primary master face)
            $dataFront = str_replace('data:image/jpeg;base64,', '', $frontData);
            $dataFront = str_replace(' ', '+', $dataFront);
            $imageFrontDecoded = base64_decode($dataFront);
            Storage::disk('local')->put('master_face/user_' . Auth::id() . '_front.jpg', $imageFrontDecoded);
            // Legacy / fallback baseline compatibility
            Storage::disk('local')->put('master_face/user_' . Auth::id() . '.jpg', $imageFrontDecoded);

            // Save left profile if provided
            $hasLeft = false;
            if (!empty($leftData)) {
                $dataLeft = str_replace('data:image/jpeg;base64,', '', $leftData);
                $dataLeft = str_replace(' ', '+', $dataLeft);
                $imageLeftDecoded = base64_decode($dataLeft);
                Storage::disk('local')->put('master_face/user_' . Auth::id() . '_left.jpg', $imageLeftDecoded);
                $hasLeft = true;
            }

            // Save right profile if provided
            $hasRight = false;
            if (!empty($rightData)) {
                $dataRight = str_replace('data:image/jpeg;base64,', '', $rightData);
                $dataRight = str_replace(' ', '+', $dataRight);
                $imageRightDecoded = base64_decode($dataRight);
                Storage::disk('local')->put('master_face/user_' . Auth::id() . '_right.jpg', $imageRightDecoded);
                $hasRight = true;
            }

            $angles = ['front'];
            if ($hasLeft) $angles[] = 'left';
            if ($hasRight) $angles[] = 'right';
            
            // WRITE AUTOMATED AUDIT LOG FOR ENROLLMENT
            \App\Models\AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'biometrics.enrolled',
                'model_type' => \App\Models\User::class,
                'model_id' => Auth::id(),
                'new_values' => [
                    'registered_angles' => $angles,
                    'is_complete' => count($angles) === 3
                ],
                'metadata' => [
                    'mode' => 'client_side_multi_angle',
                    'timestamp' => now()->toIso8601String(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            session()->flash('success', 'Templat biometrik wajah multi-sudut berhasil didaftarkan! Vektor terverifikasi kini aktif.');
            $this->step = 'overview';
        } catch (\Exception $e) {
            session()->flash('error', 'Pendaftaran wajah gagal: ' . $e->getMessage());
        }
    }

    public function deleteFace()
    {
        $files = [
            'master_face/user_' . Auth::id() . '.jpg',
            'master_face/user_' . Auth::id() . '_front.jpg',
            'master_face/user_' . Auth::id() . '_left.jpg',
            'master_face/user_' . Auth::id() . '_right.jpg',
        ];
        foreach ($files as $file) {
            if (Storage::disk('local')->exists($file)) {
                Storage::disk('local')->delete($file);
            }
        }

        // WRITE AUTOMATED AUDIT LOG FOR SELF DE-AUTHORIZATION
        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'biometrics.deleted',
            'model_type' => \App\Models\User::class,
            'model_id' => Auth::id(),
            'old_values' => [
                'status' => 'secure_baseline'
            ],
            'metadata' => [
                'mode' => 'self_deauthorization',
                'timestamp' => now()->toIso8601String(),
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Templat biometrik wajah berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.profile.profile-index');
    }
}
