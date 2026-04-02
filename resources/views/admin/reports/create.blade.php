@extends('layouts.admin.app')

@section('title', 'Buat Laporan Baru')

@section('admin-content')
<div class="page-container">
    <header class="page-header">
        <div>
            <h1>Buat Laporan Baru</h1>
            <p class="subtitle">Laporkan masalah atau berikan saran</p>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn-ghost">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </header>

    @if($errors->any())
        <div class="alert alert-error" role="alert">
            <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div>
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <form action="{{ route('admin.reports.store') }}" method="POST" id="reportForm" class="form-layout">
        @csrf

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">1</span>
                Detail Laporan
            </h2>

            <div class="grid grid-cols-2">
                <div class="form-group full-width">
                    <label for="judul" class="form-label">Judul Laporan <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <input type="text"
                               id="judul"
                               name="judul"
                               value="{{ old('judul') }}"
                               placeholder="Contoh: AC Rusak di Ruang 302"
                               class="form-input @error('judul') is-invalid @enderror"
                               required>
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16v16H4z"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                            </svg>
                        </span>
                    </div>
                    @error('judul')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group full-width">
                    <label for="deskripsi" class="form-label">Deskripsi Lengkap <span class="required">*</span></label>
                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="5"
                              placeholder="Jelaskan detail laporan..."
                              class="form-textarea @error('deskripsi') is-invalid @enderror"
                              required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit_id" class="form-label">Unit <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <select id="unit_id" name="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Pilih unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('unit_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tipe" class="form-label">Tipe Laporan <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <select id="tipe" name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                            <option value="masalah" {{ old('tipe') == 'masalah' ? 'selected' : '' }}>Masalah</option>
                            <option value="saran" {{ old('tipe') == 'saran' ? 'selected' : '' }}>Saran</option>
                            <option value="keluhan" {{ old('tipe') == 'keluhan' ? 'selected' : '' }}>Keluhan</option>
                            <option value="lainnya" {{ old('tipe') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    @error('tipe')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </section>

        <section class="form-card">
            <h2 class="section-title">
                <span class="section-number">2</span>
                Prioritas
            </h2>

            <div class="form-group full-width">
                <label class="form-label">Tingkat Prioritas</label>
                <div class="status-options">
                    <label class="status-option">
                        <input type="radio" name="prioritas" value="rendah" {{ old('prioritas', 'sedang') == 'rendah' ? 'checked' : '' }}>
                        <span class="status-badge low">Rendah</span>
                    </label>
                    <label class="status-option">
                        <input type="radio" name="prioritas" value="sedang" {{ old('prioritas', 'sedang') == 'sedang' ? 'checked' : '' }}>
                        <span class="status-badge medium">Sedang</span>
                    </label>
                    <label class="status-option">
                        <input type="radio" name="prioritas" value="tinggi" {{ old('prioritas') == 'tinggi' ? 'checked' : '' }}>
                        <span class="status-badge high">Tinggi</span>
                    </label>
                    <label class="status-option">
                        <input type="radio" name="prioritas" value="kritis" {{ old('prioritas') == 'kritis' ? 'checked' : '' }}>
                        <span class="status-badge critical">Kritis</span>
                    </label>
                </div>
            </div>
        </section>

        <div class="form-actions">
            <a href="{{ route('admin.reports.index') }}" class="btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <span class="btn-text">Buat Laporan</span>
                <span class="btn-loader" hidden>
                    <svg class="spinner" width="16" height="16" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" stroke-dasharray="60" stroke-dashoffset="20"/>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
</div>
@endsection