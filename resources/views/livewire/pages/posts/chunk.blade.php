<div>
    @foreach($posts as $index => $post)
        <x-posts.card style="animation-delay: {{ $index * 0.1 }}s" :$post :key="$post->id"/>
    @endforeach
</div>
