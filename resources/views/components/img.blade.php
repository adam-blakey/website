@props(['src', 'sizes'])

@php($image = new \App\Helpers\Image($src))

<img src="{{ $image->url() }}" srcset="{{ $image->srcset() }}" sizes="{{ $sizes }}" {{ $attributes }}>
