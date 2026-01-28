@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Sélectionner...',
    'required' => false,
    'allowClear' => true,
    'multiple' => false,
    'optionValue' => 'id',
    'optionLabel' => 'name',
])

@php
    $fieldId = $id ?? $name;
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $fieldId }}" class="form-label">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
    @endif

    <div x-data="tomSelect({
        id: '{{ $fieldId }}',
        placeholder: '{{ $placeholder }}',
        allowClear: {{ $allowClear ? 'true' : 'false' }}
    })">
        <select
                id="{{ $fieldId }}"
                name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                class="form-select"
                @if($multiple) multiple @endif
                @if($required) required @endif
                {{ $attributes }}>

            @if(!$multiple && !$required)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach($options as $option)
                @php
                    $value = is_array($option) ? $option[$optionValue] : $option->$optionValue;
                    $label = is_array($option) ? $option[$optionLabel] : $option->$optionLabel;
                    $isSelected = $selected == $value;
                @endphp
                <option value="{{ $value }}" @if($isSelected) selected @endif>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
</div>
