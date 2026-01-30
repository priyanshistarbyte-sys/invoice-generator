@extends('layouts.main')
@section('page-title', 'Invoice')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Companies</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table" id="companies-table">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Total Invoices</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#companies-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('companies.list') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'invoices_count', name: 'invoices_count'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endpush