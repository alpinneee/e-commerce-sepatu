@extends('layouts.admin')

@section('title', 'Security')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Security Settings</h1>
            <p class="text-gray-600">Manage account security and access controls</p>
        </div>
    </div>

    <!-- Security Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Security Status</p>
                    <p class="text-xl font-bold text-green-600">Secure</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Admin Users</p>
                    <p class="text-xl font-bold text-gray-900">{{ $totalAdmins }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Recent Logins</p>
                    <p class="text-xl font-bold text-gray-900">{{ $recentLogins }}</p>
                    <p class="text-xs text-gray-500">Last 7 days</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                <p class="text-sm text-gray-600">Update your password to keep your account secure</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.security.password') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                <p class="text-sm text-gray-600">Your current account details</p>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Name</span>
                    <span class="text-sm text-gray-900">{{ $user->name }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Email</span>
                    <span class="text-sm text-gray-900">{{ $user->email }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Role</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        Admin
                    </span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Last Login</span>
                    <span class="text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm font-medium text-gray-600">Account Created</span>
                    <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Features -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Security Features</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Password Protection</p>
                            <p class="text-xs text-gray-500">Strong password required</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Active</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Role-Based Access</p>
                            <p class="text-xs text-gray-500">Admin permissions only</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Active</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Session Timeout</p>
                            <p class="text-xs text-gray-500">Auto logout after inactivity</p>
                        </div>
                    </div>
                    <span class="text-yellow-600 text-sm font-medium">Configured</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Activity Logging</p>
                            <p class="text-xs text-gray-500">Track admin actions</p>
                        </div>
                    </div>
                    <span class="text-green-600 text-sm font-medium">Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($recentActivities as $activity)
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $activity['action'] }}</p>
                            <p class="text-xs text-gray-500">{{ $activity['user'] }} • {{ $activity['ip'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $activity['time']->diffForHumans() }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($activity['status']) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection