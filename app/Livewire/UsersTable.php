<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;


    public $perPage = "5";
    #[Url(history:true)]
    public $search = "";
    #[Url(history:true)]
    public $admin = '';
    #[Url(history:true)]
    public $sortBy ='created_at';
    #[Url(history:true)]
    public $sortDir = 'DESc';

    public function setSortBy($field){

        if($this->sortBy===$field){
            $this->sortDir= ($this->sortDir==="ASC"?"DESC":"ASC");
            return;
        }
        $this->sortBy =$field;
        $this->sortDir = 'DESC';
    }
    public function delete(User $user){
        $user->delete();
    }

    public function render()
    {
        return view(
            'livewire.users-table',
            [
                "users" => User::search($this->search)
                            ->when($this->admin !=="",function($query){
                                $query->where("is_admin",$this->admin);
                            })
                            ->orderBy($this->sortBy,$this->sortDir)
                            ->paginate($this->perPage)
            ]
        );
    }
}
