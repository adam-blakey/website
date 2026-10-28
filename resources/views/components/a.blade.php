@props([
    'href' => null,
    'view' => null,
    'noExternalIcon' => false,
    'noUnderline' => false,
    'ariaLabel' => ''
])

@php
    use Illuminate\Support\Str;

    // Determine the URL - href takes precedence over view
    if ($href != null) {
        $url = $href;
        $isExternal = Str::startsWith($url, ['http://', 'https://']) && !Str::startsWith($url, request()->root());
    }
    else {
        $url = $view ? route($view) : '';
        $isExternal = false;
    }

    $target = $isExternal ? '_blank' : null;
    $underlineClass = $noUnderline ? '' : 'underline';
    $ariaLabel = $isExternal ? $ariaLabel . ' (opens in a new tab)' : $ariaLabel;
@endphp

@if ($url == "")
    <span {{ $attributes }}>
        {{ $slot }}
        @if($isExternal && !$noExternalIcon)
            {!! \Mdi\Mdi::mdi('open-in-new', 'inline ml-1', 16) !!}
        @endif
    </span>
@else
    <a href="{{ $url }}"
        {{ $target ? "target=$target" : '' }}
        {{ $attributes->merge(['class' => $underlineClass, 'aria-label' => $ariaLabel]) }}>
        {{ $slot }}
        @if($isExternal && !$noExternalIcon)
            {!! \Mdi\Mdi::mdi('open-in-new', 'inline ml-1', 16) !!}
        @endif
    </a>
@endif
