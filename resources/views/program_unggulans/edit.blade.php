@extends('layouts.admin')

@section('title', 'Edit Program Unggulan - ' . $program->judul)

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Edit Program Unggulan: {{ $program->judul }}</h6>
            <span class="text-xs text-secondary-light">
                Perbarui narasi, pilar, kegiatan, atau foto dokumentasi program unggulan MIN 1 Ciamis.
            </span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if ($program->is_active)
                <a href="{{ route('program-unggulan.detail', $program->slug) }}" target="_blank"
                    class="btn btn-outline-success radius-8 px-14 py-8 text-xs fw-bold">
                    <i class="fas fa-external-link-alt"></i> Pratinjau Publik
                </a>
            @endif
            <a href="{{ route('program-unggulan.index') }}" class="btn btn-secondary radius-8 px-14 py-8 text-xs fw-bold">
                &larr; Kembali ke Daftar
            </a>
        </div>
    </div>

    @include('program_unggulans._form', ['program' => $program])
@endsection