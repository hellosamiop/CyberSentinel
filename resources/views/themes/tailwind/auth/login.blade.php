@extends('theme::layouts.app')

@section('content')

    <div class="flex flex-col justify-center py-20 sm:px-6 lg:px-8">
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="px-4 py-8 bg-white border shadow border-gray-50 sm:rounded-lg sm:px-10">
                <div class="sm:mx-auto sm:w-full sm:max-w-md mb-5">
                    <h2 class="mt-6 text-3xl font-bold text-center text-gray-900 lg:text-5xl">
                        Login
                    </h2>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div>
                        @if(setting('auth.email_or_username') && setting('auth.email_or_username') == 'username')
                            <label for="username" class="block text-sm font-medium leading-5 text-gray-700">Username</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input id="username" type="username" name="username" required class="w-full form-input" autofocus>
                            </div>
                            @if ($errors->has('username'))
                                <div class="mt-1 text-red-500">
                                    {{ $errors->first('username') }}
                                </div>
                            @endif
                        @else
                            <label for="email" class="block text-sm font-medium leading-5 text-gray-700">Email</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input id="email" type="email" name="email" required class="w-full form-input" autofocus>
                            </div>
                            @if ($errors->has('email'))
                                <div class="mt-1 text-red-500">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="mt-6">
                        <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <input id="password" type="password" name="password" required class="w-full form-input">
                        </div>
                        @if ($errors->has('password'))
                            <div class="mt-1 text-red-500">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                    <input  name="remember" type="hidden" value="1" >
                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white transition duration-150 ease-in-out border border-transparent rounded-md bg-wave-600 hover:bg-wave-500 focus:outline-none focus:border-wave-700 focus:shadow-outline-wave active:bg-wave-700">
                                Sign in
                            </button>
                        </span>
                    </div>
                </form>
                <div class="text-sm text-center mt-3">
                    <a href="{{ route('password.request') }}" class="font-medium transition duration-150 ease-in-out text-wave-600 hover:text-wave-500 focus:outline-none focus:underline">
                        Forgot your password?
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
