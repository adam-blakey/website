@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])

<div class="mb-3">
    <label class="form-label @if ($required) required @endif" for="{{ $name }}">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" @required($required) {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $name }}" name="{{ $name }}" @required($required) {{ $attributes->class(['form-select', 'is-invalid' => $errors->has($name)]) }}>
            {{ $slot }}
        </select>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @required($required) {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    @if ($hint)
        <small class="form-hint">{{ $hint }}</small>
    @endif
</div>
