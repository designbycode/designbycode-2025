@props(['author'])

<img class=" size-10 block rounded-full bg-primary " src="{{ $author->avatar() }}" alt="{{ $author->name }}">
