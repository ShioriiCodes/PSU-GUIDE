@extends('layouts.view')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-md rounded-2xl px-6 py-8 sm:px-10 sm:py-10">
    <h1 class="text-2xl sm:text-3xl text-black font-bold mb-6">Edit User</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('account.update', $student->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Role -->
            <div class="text-black">
                <label for="role" class="block text-sm font-medium text-slate-600 mb-1">Role</label>
                <select name="role" id="role" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
                    @foreach ($roles as $role)

                        <option value="{{ $role }}" {{ $student->role === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department -->
            <div class="text-black">
                <label for="department_id" class="block text-sm font-medium text-slate-600 mb-1">Department</label>
                <select name="department_id" id="department_id" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ $student->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <!-- Password -->
        <div class="text-black">
            <label for="password" class="block text-sm font-medium text-slate-600 mb-1">Password</label>
            <input type="password" name="password" id="password" placeholder="Leave blank to keep current password" class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm new password" class="w-full border rounded-lg p-2.5 mt-3 focus:outline-none focus:ring-2 focus:ring-[#E17C5F]">
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center text-black">
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Back</a>
            <button type="submit" class="px-4 py-2 bg-[#E17C5F] text-white rounded-lg hover:bg-[#c05e4d]">Update</button>
        </div>
    </form>

</div>
@endsection
