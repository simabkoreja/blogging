<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blogs') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <a href="{{ route('blogs.create') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                Create Blog
            </a>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 bg-white font-medium mb-4 p-4 sm:rounded-lg text-green-600 text-sm"
                :status="session('status')" />
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($blogs->count())
            @foreach ($blogs as $blog)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="post-body">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <div class="author-display">
                            <small>{{"Author: ". ($blog->user->name)??"User" }}</small>
                            <br>
                            <small>{{"Published On: ". ($blog->created_at->diffForHumans())??"User" }}</small>
                        </div>

                        @if (auth()->id() == $blog->user_id || auth()->user()->role == 'admin')
                        <div class="post-action-btns">
                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <a href="{{ route('blogs.edit', $blog->id) }}" class="edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button class="delete" onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        @endif

                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $blog->title }}
                        </h2>
                        <p class="text-sm">{{ $blog->content }}</p>
                        @if (!empty($blog->image))
                        <img src="{{ asset($blog->image) }}" alt="Blog Image" width="100" />
                        @endif
                        
                        <form action="{{ route('blogs.comment.store') }}" method="POST" class="mb-4">
                            @csrf
                            
                            <!-- Blog ID -->
                            <x-input name="blog_id" type="hidden" :value="$blog->id"/>

                            <!-- Comment -->
                            <div class="mb-4 mt-4">
                                <x-label :value="__('Leave a Comment')" />
                                <x-textarea class="block mt-1 w-full" name="comment" :value="old('comment')" required />
                            </div>

                            <!-- Submit -->
                            <x-button>
                                {{ __('Submit') }}
                            </x-button>
                        </form>
                        <div class="comments">
                            
                            @foreach ($blog->comments as $comment)
                            
                            <div class="p-6 bg-white border border-gray-200 comment px-6">
                                <h3 class="font-semibold comment_author">{{ $comment->user->name }}</h3> 
                                <small class="p-2">{{ $comment->created_at->diffForHumans() }}</small>
                                <p>{{ $comment->comment }}</p>

                                @if (auth()->id() == $comment->user_id || auth()->user()->role == 'admin')
                                <div class="comment-action-btns">
                                    <form action="{{ route('blogs.comment.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <x-input name="comment_id" type="hidden" :value="$comment->id"/>

                                        <x-nav-link x-data="$comment" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $comment->id }}')" href="#" class="edit">
                                            <i class="fa fa-pencil"></i>
                                        </x-nav-link>
                                        <button class="delete" onclick="return confirm('Are you sure?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    <x-modal name="confirm-user-deletion-{{ $comment->id }}" :show="$errors->commentUpdation->isNotEmpty()" focusable>
        
                                        <form method="post" action="{{ route('blogs.comment.update') }}" class="p-6">
                                            @csrf
                                            @method('post')
                                
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Edit Comment') }}
                                            </h2>
                                
                                            
                                            <div class="mt-6">
                                                <x-input-label for="comment" value="{{ __('Comment') }}" class="sr-only" />

                                                <x-input
                                                    name="comment_id"
                                                    type="hidden"
                                                    :value="$comment->id"
                                                />
                                
                                                <x-textarea
                                                    name="comment"
                                                    class="mt-1 block w-3/4"
                                                    placeholder="{{ __('Comment') }}"
                                                >{{ $comment->comment }}</x-textarea>
                                
                                                <x-input-error :messages="$errors->commentUpdation->get('comment')" class="mt-2" />
                                            </div>
                                
                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('Cancel') }}
                                                </x-secondary-button>
                                
                                                <x-primary-button class="ml-3">
                                                    {{ __('Submit') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @else
            <div class="p-6 bg-white border-b border-gray-200">
                No Blogs!
            </div>
            @endif

            @if ($blogs->count())
            <div>
                {{ $blogs->links() }}
            </div>
            @endif
        </div>

    </div>

    

    <x-slot name="style">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .post-body,.comment {
                position: relative;
            }

            .author-display {
                position: absolute;
                right: 10px;
                top: 3px;
            }

            .post-action-btns {
                position: absolute;
                top: 0px;
                right: 0;
                display: none;
            }

            .comment-action-btns {
                position: absolute;
                top: 0px;
                right: 0;
                display: none;
            }

            .post-body:hover .post-action-btns {
                display: block;
            }

            .comment:hover .comment-action-btns {
                display: block;
            }

            .edit,
            .delete {
                border: 1px solid;
                color: white;
                padding: 1px 5px;
                max-width: 27px;
                border-radius: 50%;
            }

            .edit {
                background-color: lightgreen;
            }

            .delete {
                background-color: red;
            }

            i.fa {
                width: 16px;
            }

            .comment_author{
                display: inline-block;
            }
        </style>
    </x-slot>
</x-app-layout>