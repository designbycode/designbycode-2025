<div>
    @foreach($posts as $post)
        <x-posts.card :$post :key="$post->id"/>
    @endforeach
</div>
