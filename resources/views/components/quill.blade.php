<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    @endif

    <div x-data="quill({
        name: '{{ $name }}',
        height: {{ $height }},
        value: {{ json_encode(old($name, $value)) }}
    })">
        <div id="quill-{{ $name }}" style="height: {{ $height }}px;"></div>
        <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}">
    </div>
</div>
