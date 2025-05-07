<?php

namespace App\Livewire\User;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class IndexUser extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::paginate(10);

        return view('livewire.user.index-user', [
            'users' => $users
        ]);
    }

    public function deleteUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            $user->delete();

            DB::commit();
        } catch (Exception $e) {
            info('delete user - ' . $e->getMessage());
            DB::rollBack();
        }
    }

    public function showUser($id)
    {
        redirect()->route('show.user', $id);
    }
}
