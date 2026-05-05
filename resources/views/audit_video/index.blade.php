@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Audit Harian</h5>
    <table class="table datatable" style="margin-top: 15px">
        <thead class="thead-dark">
            <tr style="text-align: center">
                <th>No</th>
                <th>Tanggal</th>
                <th>Penginput</th>
                <th>Toko</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <?php $no =1; ?>
        <tbody>
            @foreach($data as $dt)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $dt->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $dt->user->name }}</td>
                <td>{{ $dt->toko }}</td>
                <td>
                    <a href="{{ route('audit-video.show', $dt->id) }}" class="btn btn-primary btn-sm">Detail </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $dt->id }})">
                        Hapus
                    </button>
                    
                    <form id="delete-form-{{ $dt->id }}" action="{{ route('audit-video.delete', $dt->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
