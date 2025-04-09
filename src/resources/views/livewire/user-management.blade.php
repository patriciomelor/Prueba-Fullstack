    <?php

use function Livewire\Volt\{state, rules, computed, usesPagination, on, mount};
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

usesPagination(theme: 'tailwind');

state([
    'name',
    'apellido',
    'email',
    'nro_telefono',
    'password',
    'password_confirmation',
    'userId' => null,
    'search' => '',
    'isOpen' => false,
]);

rules(fn () => [
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
]);

$validationAttributes = [
    'name' => 'nombre',
    'apellido' => 'apellido',
    'email' => 'correo electrónico',
    'nro_telefono' => 'número de teléfono',
    'password' => 'contraseña',
    'password_confirmation' => 'confirmación de contraseña',
];

$users = computed(function () {

    return User::where(function ($query) {
        $query->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('apellido', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%');
    })
    ->paginate(10);
});

$resetInputFields = function () {
    $this->reset(['name', 'apellido', 'email', 'nro_telefono', 'password', 'password_confirmation', 'userId']);
    $this->resetErrorBag();
};

$openModal = function () {
    $this->resetErrorBag();
    $this->isOpen = true;
};

$closeModal = function () {
    $this->isOpen = false;
};

$create = function () use ($resetInputFields, $openModal) {
    $resetInputFields();
    $openModal();
};

$edit = function ($id) use ($openModal) {
    $user = User::findOrFail($id);
    $this->userId = $id;
    $this->name = $user->name;
    $this->apellido = $user->apellido;
    $this->email = $user->email;
    $this->nro_telefono = $user->nro_telefono;
    $this->password = null;
    $this->password_confirmation = null;
    $openModal();
};

$store = function () use ($closeModal, $resetInputFields) {
    $validatedData = $this->validate($this->rules());

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

    session()->flash('message', $this->userId ? 'Usuario actualizado correctamente.' : 'Usuario creado correctamente.');

    $closeModal();
    $resetInputFields();
};

$delete = function ($id) {
    User::find($id)->delete();
    session()->flash('message', 'Usuario eliminado correctamente.');
};

$resetPageOnSearch = function() {
    $this->resetPage();
};

?>

<div>
    <h2 class="text-2xl font-semibold mb-4">Gestión de Usuarios (Volt)</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Cerrar</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.03a1.2 1.2 0 1 1-1.697-1.697l3.03-2.651-3.03-2.651a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.03a1.2 1.2 0 1 1 1.697 1.697l-3.03 2.651 3.03 2.651a1.2 1.2 0 0 1 0 1.697z"/></svg>
            </button>
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Crear Nuevo Usuario
        </button>

         <input wire:model.live="search" wire:input="resetPageOnSearch" type="text" placeholder="Buscar por nombre, apellido o email..." class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-1/3">
    </div>


    @if($isOpen)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="store">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    {{ $userId ? 'Editar Usuario' : 'Crear Usuario' }}
                                </h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre:</label>
                                        <input type="text" wire:model="name" id="name" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                     <div>
                                        <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido:</label>
                                        <input type="text" wire:model="apellido" id="apellido" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                     <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico:</label>
                                        <input type="email" wire:model="email" id="email" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                     <div>
                                        <label for="nro_telefono" class="block text-sm font-medium text-gray-700">Nro. Teléfono:</label>
                                        <input type="text" wire:model="nro_telefono" id="nro_telefono" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @error('nro_telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                     <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña:</label>
                                        <input type="password" wire:model="password" id="password" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="{{ $userId ? 'Dejar en blanco para no cambiar' : '' }}">
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña:</label>
                                        <input type="password" wire:model="password_confirmation" id="password_confirmation" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button wire:click="closeModal" type="button" class="mt
</div>
