@extends('layouts.student.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <header class="flex items-center gap-6 mb-12">
            <a href="{{ route('student.profile.show') }}"
                class="w-14 h-14 flex items-center justify-center bg-white rounded-2xl shadow-xl hover:bg-primary/10 transition-all active:scale-95">
                <span class="material-symbols-outlined text-3xl text-primary">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tighter text-on-surface">Edit Profile</h1>
                <p class="text-on-surface-variant text-xl mt-1">Keep your information fresh and professional</p>
            </div>
        </header>

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PUT')

            <div class="bg-white p-10 md:p-12 rounded-3xl shadow-xl text-center">
                <div class="mx-auto relative group w-48 h-48">
                    <div
                        class="absolute -inset-4 bg-linear-to-tr from-primary via-secondary-container to-transparent rounded-full blur-xl opacity-30 group-hover:opacity-50 transition duration-700">
                    </div>

                    <div class="relative w-48 h-48 rounded-full overflow-hidden border-8 border-white shadow-2xl">
                        <img id="preview-image"
                            src="{{ $profile->photo_url }}"
                            alt="{{ $profile->name }}" class="w-full h-full object-cover">
                    </div>

                    <label for="photo"
                        class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-white text-6xl">photo_camera</span>
                    </label>

                    <label for="photo"
                        class="absolute -bottom-2 -right-2 bg-primary text-white p-4 rounded-2xl shadow-xl hover:bg-primary/80 hover:scale-110 transition-all cursor-pointer">
                        <span class="material-symbols-outlined">edit</span>
                    </label>
                </div>

                <p class="mt-6 text-sm font-medium text-primary tracking-wider">CHANGE PROFILE PHOTO</p>
                <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden" onchange="previewImage(event)">
                @error('photo')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            @include('student.profile.partials.form', ['profile' => $profile])

            <div class="flex flex-col sm:flex-row gap-4 pt-8">
                <button type="submit"
                    class="flex-1 bg-red-600 text-white font-bold py-6 rounded-3xl shadow-2xl hover:bg-red-700 transition-all active:scale-[0.97] text-xl">
                    Save Changes
                </button>

                <a href="{{ route('student.profile.show') }}"
                    class="flex-1 bg-white border-2 border-gray-200 text-gray-700 font-bold py-6 rounded-3xl hover:bg-gray-50 transition-all active:scale-[0.97] text-xl text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview-image');
                if (preview) {
                    preview.src = reader.result;
                }
            }
            if (event.target.files && event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        document.getElementById('photo')?.addEventListener('change', previewImage);
    </script>
@endsection