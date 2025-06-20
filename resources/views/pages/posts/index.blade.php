<x-app-layout>
    <div class="wrapper my-6">
        <h1 class="text-2xl font-bold tracking-tighter">Articles</h1>
        @foreach($posts as $post)
            <div class="post-item my-4 p-4 border rounded-lg">
                <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
                {{--                <p class="text-gray-700">{{ Str::limit($post->content, 150) }}</p>--}}
                <a href="{{ route('posts.show', $post) }}" class="text-blue-500 hover:underline">Read more</a>
            </div>
        @endforeach
    </div>
</x-app-layout>
