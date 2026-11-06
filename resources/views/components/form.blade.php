@props([
'id' => null,
'action' => null,
'method' => 'POST',
])

@php
$method = strtoupper($method);

$htmlMethod = in_array($method, ['GET', 'POST'])
? $method
: 'POST';

$enctype ??= 'multipart/form-data';
@endphp

<form @if($id) id="{{ $id }}" @endif @if($action) action="{{ $action }}" @endif method="{{ $htmlMethod }}"
    enctype="multipart/form-data" novalidate>
    @csrf
    @if(!in_array($method, ['GET', 'POST']))
    @method($method)
    @endif

    {{ $slot }}
</form>