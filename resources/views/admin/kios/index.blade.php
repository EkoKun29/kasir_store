@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Data Kios</h5>
        <a href="#">
            <button class="btn btn-primary" style="margin-top: 10px">Input Kios</button>
        </a>
        <table class="table datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kios</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            @php $no = 1; @endphp
            <tbody>
                @foreach ($kios as $item)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $item->kios }}</td>
                    <td>
                        <a href="{{ route('kios.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                            Hapus
                        </button>

                        <form id="delete-form-{{ $item->id }}" action="{{ route('kios.destroy', $item->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Yakin ingin menghapus data ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection
