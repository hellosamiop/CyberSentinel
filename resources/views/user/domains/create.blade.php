@extends('theme::layouts.app')
@section('content')
    <div class="py-20 mx-auto max-w-7xl">
        <div class="container mx-auto px-4">
            <div class="mx-auto bg-white p-6 shadow" style="border-radius: 15px">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" style="border-radius: 15px;" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h2 class="text-2xl font-bold mb-4">Create New Domain</h2>
                <form action="{{ route('domains.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex gap-6 flex-col sm:flex-row">
                        <div class="w-full">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-semibold text-gray-600">Name:</label>
                                <input type="text" class="mt-1 p-2 w-full border" style="border-radius: 15px" name="name" id="name" required>
                            </div>

                            <div class="mb-4">
                                <label for="domain_url" class="block text-sm font-semibold text-gray-600">Domain URL:</label>
                                <input type="url" class="mt-1 p-2 w-full border"  style="border-radius: 15px" name="domain_url" id="domain_url"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label for="industry_id" class="block text-sm font-semibold text-gray-600">Industry:</label>
                                <select name="industry_id" id="industry_id" class="mt-1 p-2 w-full border"  style="border-radius: 15px" required>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="country_id" class="block text-sm font-semibold text-gray-600">Country:</label>
                                <select name="country_id" id="country_id" class="mt-1 p-2 w-full border"  style="border-radius: 15px" required>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-full">
{{--                            <div class="border mb-3 bg-transparent" style="border-radius: 15px; width: 100%; height: 200px;background-repeat: no-repeat;background-size: cover;background-clip: border-box; background-image: url({{asset('assets/images/default_logo.jpg')}})">--}}
                            <div class=" mb-3" style="border-radius: 15px; width: 100%;">
                                <img id="logo_preview" class="border" src="{{asset('assets/images/default_logo.jpg')}}" style="border-radius: 15px;width: 100%; height: 250px;"/>
                            </div>
                            <div class="mb-4">
                                <label for="logo" class="block text-sm font-semibold text-gray-600">Logo:</label>
                                <input type="file" class="mt-1 p-2 w-full  border"  style="border-radius: 15px"
                                       name="logo"
                                       id="logo" accept="image/*">

                            </div>
                        </div>
                    </div>



                    <div class="flex justify-end items-center space-x-4">
                        <button type="submit"
                                class="bg-wave-500 hover:bg-wave-700 text-white font-bold py-2 px-4 rounded">
                            Create
                        </button>
                        <a href="{{ route('domains.index') }}"
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        document.getElementById('logo').addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('logo_preview').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
