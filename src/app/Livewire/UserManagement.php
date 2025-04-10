<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination; 
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout; 

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination; 

    
    public $name, $apellido, $email, $nro_telefono, $password, $password_confirmation;
    public $userId = null;
    public $search = '';
    public $isOpen = false; 

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'nro_telefono' => 'nullable|string|max:20',
            'password' => [
                Rule::requiredIf(!$this->userId),
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    protected $validationAttributes = [
        'name' => 'nombre',
        'apellido' => 'apellido',
        'email' => 'correo electrónico',
        'nro_telefono' => 'número de teléfono',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where(function($query) {
                        $query->where('name', 'like', '%'.$this->search.'%')
                              ->orWhere('apellido', 'like', '%'.$this->search.'%')
                              ->orWhere('email', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('id')
                    ->paginate(10);

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->apellido = $user->apellido;
        $this->email = $user->email;
        $this->nro_telefono = $user->nro_telefono;
        $this->password = null;
        $this->password_confirmation = null;
        $this->openModal();
    }

    public function store()
    {
        $validatedData = $this->validate(); 

        $dataToSave = [
            'name' => $validatedData['name'],
            'apellido' => $validatedData['apellido'],
            'email' => $validatedData['email'],
            'nro_telefono' => $validatedData['nro_telefono'],
        ];

        if (!empty($validatedData['password'])) {
            $dataToSave['password'] = Hash::make($validatedData['password']);
        }

        User::updateOrCreate(['id' => $this->userId], $dataToSave);

        session()->flash('message',
            $this->userId ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    // Elimina usuario
    public function delete($id)
    {
  
        User::find($id)->delete();
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function openModal()
    {
        $this->resetErrorBag(); 
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->reset(['name', 'apellido', 'email', 'nro_telefono', 'password', 'password_confirmation', 'userId']);
        $this->resetErrorBag();
    }
    public function toggleActivation($userId)
{
    if (auth()->id() == $userId) {
        session()->flash('error', 'No puedes desactivar tu propia cuenta.');
        return;
    }

    $user = User::find($userId);
    if ($user) {
        $user->is_active = !$user->is_active; 
        $user->save();
        $status = $user->is_active ? 'activado' : 'desactivado';
        session()->flash('message', "Usuario {$status} correctamente.");
    }
}

}