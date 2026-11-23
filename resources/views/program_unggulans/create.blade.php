@extends('layouts.admin')

@section('title', 'Tambah Program Unggulan Baru - MIN 1 Ciamis')

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-20">
        <div>
            <h6 class="fw-semibold mb-1">Tambah Program Unggulan Baru</h6>
            <span class="text-xs text-secondary-light">
                Lengkapi informasi utama di Section 1. Section Pilar &amp; Kegiatan bersifat opsional dan hanya tampil bila diisi.
            </span>
        </div>
        <a href="{{ route('program-unggulan.index') }}" class="btn btn-secondary radius-8 px-14 py-8 text-xs fw-bold">
            &larr; Kembali ke Daftar Program
        </a>
    </div>

    @include('program_unggulans._form')
@endsection