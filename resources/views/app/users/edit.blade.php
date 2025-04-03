<x-app-layout>


    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Update User') }}
            </h2>
            <x-link-button href="{{ route('tenant.create') }}">Create Tenant</x-link-button>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('users.update',$user->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name',$user->name)" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email',$user->email)" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        
                            
                            <div class="mt-4">
                                <x-input-label for="roles" :value="__('Roles')" />
                                <div id="hiddenInputsContainer">
                                    @foreach ($user->roles as $role)
                                        <input type="hidden" name="roles[]" value="{{ $role->id }}">
                                    @endforeach
                                </div>
                                
                                <div id="selectedRoles" class="flex flex-wrap gap-2 border border-gray-300 rounded-md p-2 cursor-pointer">
                                    @foreach ($user->roles as $role)
                                        <span class="role-tag bg-indigo-500 text-white px-3 py-1 rounded-md text-sm cursor-pointer"
                                            data-role-id="{{ $role->id }}">
                                            {{ $role->name }} ✕
                                        </span>
                                    @endforeach
                                    <span id="addRoleText" class="text-gray-500 text-sm">{{ count($user->roles) ? '+ Add more' : 'Select roles' }}</span>
                                </div>
                                
                                <div id="rolesDropdownContainer" class="relative hidden">
                                    <select id="rolesDropdown" class="block w-full border border-gray-300 rounded-md shadow-sm bg-white mt-1 p-2" multiple>
                                        @foreach ($roles as $role)
                                            @if (!in_array($role->id, $user->roles->pluck('id')->toArray()))
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                            </div>
                        
                        
                        
                
                        <div class="flex items-center justify-end mt-4">
                            
                
                            <x-primary-button class="ms-4">
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#selectedRoles').click(() => $('#rolesDropdownContainer').toggleClass('hidden'));
        
        $('#rolesDropdown').change(function() {
            let selected = $(this).find(':selected');
            if (selected.val()) {
                $('#selectedRoles #addRoleText').before(`
                    <span class="role-tag bg-indigo-500 text-white px-3 py-1 rounded-md text-sm cursor-pointer"
                        data-role-id="${selected.val()}">
                        ${selected.text()} x
                    </span>`);
                selected.remove();
                $('#hiddenInputsContainer').append(`<input type="hidden" name="roles[]" value="${selected.val()}">`);
            }
        });
    
        $(document).on('click', '.role-tag', function() {
            let tag = $(this);
            $('#rolesDropdown').append(`<option value="${tag.data('role-id')}">${tag.text().replace(' ✕', '')}</option>`);
            $(`#hiddenInputsContainer input[value="${tag.data('role-id')}"]`).remove();
            tag.remove();
        });
    });
    </script>
