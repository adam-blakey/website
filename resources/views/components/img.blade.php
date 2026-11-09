@props(['src', 'sizes', 'caption' => null])

@php($image = new \App\Helpers\Image($src))

@if ($caption)
    <figure>
        <img src="{{ $image->url() }}" srcset="{{ $image->srcset() }}" sizes="{{ $sizes }}" alt="" {{ $attributes }}>
        <figcaption class="w-0 min-w-full mt-1 text-center text-sm text-gray-500">{{ $caption }}</figcaption>
    </figure>
@else
    <img src="{{ $image->url() }}" srcset="{{ $image->srcset() }}" sizes="{{ $sizes }}" alt="" {{ $attributes }}>
@endif
