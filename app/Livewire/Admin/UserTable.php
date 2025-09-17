<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $userToDelete = null;
    public $showCreateForm = false; 

    protected $updatesQueryString = ['search'];
    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($userId)
    {
        $this->userToDelete = $userId;
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function cancelCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = false;
    }

    public function deleteUser()
    {
        if ($this->userToDelete) {
            User::find($this->userToDelete)->delete();
            $this->userToDelete = null;
            session()->flash('message', 'User deleted successfully.');
        }
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-table', compact('users'))->layout('layouts.app');
    
    }
}
