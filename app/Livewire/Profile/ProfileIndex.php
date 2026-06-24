<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('My Profile')]
class ProfileIndex extends Component
{
    use WithFileUploads;

    public $step = 'overview'; // 'overview' or 'enroll'

    public $photo; // temporary upload for the profile picture

    /**
     * Auto-save the profile photo as soon as it is selected.
     */
    public function updatedPhoto()
    {
        $this->validate(
            ['photo' => 'image|max:4096'],
            ['photo.image' => 'Berkas harus berupa gambar.', 'photo.max' => 'Ukuran foto maksimal 4MB.'],
            ['photo' => 'foto profil']
        );

        $user = Auth::user();

        // Remove previous avatar to avoid orphaned files.
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $this->photo->store('avatars', 'public');
        $user->save();

        $this->photo = null;
        session()->flash('success', 'Foto profil berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $user = Auth::user();
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = null;
        $user->save();

        session()->flash('success', 'Foto profil dihapus.');
    }

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
                    'is_complete' => in_array('front', $angles, true)
                ],
                'metadata' => [
                    'mode' => 'client_side_single_angle',
                    'timestamp' => now()->toIso8601String(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            
            session()->flash('success', 'Templat biometrik wajah berhasil didaftarkan! Vektor terverifikasi kini aktif.');
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
