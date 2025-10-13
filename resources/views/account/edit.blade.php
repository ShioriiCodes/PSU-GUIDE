@extends('layouts.view')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl text-black font-bold mb-6">Edit User</h1>.

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('account.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

       <!-- Role -->
        <div class="mb-4 text-black">
            <label for="role" class="block text-sm font-medium">Role</label>
            <select name="role" id="role" class="w-full border rounded p-2">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ $student->role === $role ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Department -->
        <select name="department_id" id="department_id" class="w-full border rounded p-2 text-black">
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ $student->department_id == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>


        <!-- Password -->
        <div class="mb-4 text-black">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input type="password" name="password" id="password"
                placeholder="Leave blank to keep current password"
                class="w-full border rounded p-2">

            <input type="password" name="password_confirmation" id="password_confirmation"
                placeholder="Confirm new password"
                class="w-full border rounded p-2 mt-4">
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center text-black">
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                Back
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Update
            </button>
        </div>
    </form>

</div>
@endsection
