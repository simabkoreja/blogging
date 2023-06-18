<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add new Blog') }}
        </h2>
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white mb-4 pb-4 py-4">
            <form action="{{ route('blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <!-- Title -->
                <div class="mb-4">
                    <x-label for="title" :value="__('Title')" />

                    <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')??$blog->title"
                        required autofocus />
                </div>

                <!-- Content -->
                <div class="mb-4">
                    <x-label for="content" :value="__('Content')" />

                    <x-textarea id="content" class="block mt-1 w-full" name="content" :value="$blog->content??old('content')"
                        required />
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <x-label for="image" :value="__('Image')" />

                    <x-input id="image" class="block mt-1 w-full" type="file" name="image" :value="old('image')" accept="jpg,png,webp,jpeg,gif"
                         />
                </div>

                <!-- Image -->
                <div class="mb-4">
                    
                    <x-button>
                        {{ __('Submit') }}
                    </x-button>

                    <a href="{{ route('blogs.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>