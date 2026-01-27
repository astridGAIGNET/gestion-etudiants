@props([
'name',
'label',
'type' => 'text',
'options' => [],
'optionValue' => 'id',
'optionLabel' => 'name',
'required' => false,
'placeholder' => null,
'rows' => 3,
'height' => 300,
'allowClear' => true,
'maxOptions' => 50,
])

@php
$fieldId = 'field-' . $name . '-' . uniqid();
$isTomSelect = ($type === 'tom-select');
$isQuill = ($type === 'quill');
$isTextarea = ($type === 'textarea');
$defaultPlaceholder = $placeholder ?? ($isTomSelect ? '-- Sélectionner --' : "Entrez {$label}");
@endphp

<div class="mb-3">
    <label for="{{ $fieldId }}" class="form-label">
        {{ $label }}
        @if($required)
        <span class="text-danger">*</span>
        @endif
    </label>

    @if($isTomSelect)
    <div class="position-relative" x-data="tomSelectAutoSave({ selectId: '{{ $fieldId }}', fieldName: '{{ $name }}', placeholder: '{{ $defaultPlaceholder }}', allowClear: {{ $allowClear ? 'true' : 'false' }}, maxOptions: {{ $maxOptions }} })">
        <select id="{{ $fieldId }}" name="{{ $name }}" x-model="form.{{ $name }}" @if($required) required @endif>
            <option value="">{{ $defaultPlaceholder }}</option>
            @foreach($options as $option)
            <option value="{{ is_array($option) ? $option[$optionValue] : $option->$optionValue }}">
                {{ is_array($option) ? $option[$optionLabel] : $option->$optionLabel }}
            </option>
            @endforeach
        </select>
        <div class="position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events: none; z-index: 1000;" x-show="hasStatus('{{ $name }}')">
            <i x-bind:class="getIconClass('{{ $name }}') + ' fs-5'"></i>
        </div>
    </div>
    @elseif($isQuill)
    <div x-show="!loading">
        <div class="quill-container position-relative" x-data="quillAutoSave({ editorId: @js($fieldId . '-editor'), fieldName: @js($name) })">
            <div id="{{ $fieldId }}-editor"></div>
            <div class="position-absolute top-0 end-0 mt-2 me-2" style="pointer-events: none; z-index: 10;" x-show="hasStatus(@js($name))">
                <i x-bind:class="getIconClass(@js($name)) + ' fs-5'"></i>
            </div>
        </div>
    </div>
    @elseif($isTextarea)
    <div class="position-relative">
        <textarea id="{{ $fieldId }}" class="form-control" x-bind:class="getInputClass('{{ $name }}')" name="{{ $name }}" rows="{{ $rows }}" x-model="form.{{ $name }}" @input.debounce.1000ms="onFieldChange('{{ $name }}'); saveField('{{ $name }}')" placeholder="{{ $defaultPlaceholder }}" @if($required) required @endif></textarea>
        <div class="position-absolute top-0 end-0 mt-2 me-2" style="pointer-events: none;" x-show="hasStatus('{{ $name }}')">
            <i x-bind:class="getIconClass('{{ $name }}') + ' fs-5'"></i>
        </div>
    </div>
    @else
    <div class="position-relative">
        <input id="{{ $fieldId }}" type="{{ $type }}" class="form-control pe-5" x-bind:class="getInputClass('{{ $name }}')" name="{{ $name }}" x-model="form.{{ $name }}" @input.debounce.1000ms="onFieldChange('{{ $name }}'); saveField('{{ $name }}')" placeholder="{{ $defaultPlaceholder }}" @if($required) required @endif>
        <div class="position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events: none;" x-show="hasStatus('{{ $name }}')">
            <i x-bind:class="getIconClass('{{ $name }}') + ' fs-5'"></i>
        </div>
    </div>
    @endif

    <div class="form-text" style="min-height: 1.5rem;">
        <template x-if="getStatusMessage('{{ $name }}')">
            <span x-bind:class="getStatusMessage('{{ $name }}').class">
                <i x-bind:class="'bi ' + getStatusMessage('{{ $name }}').icon"></i>
                <span x-text="getStatusMessage('{{ $name }}').text"></span>
            </span>
        </template>
    </div>
</div>
