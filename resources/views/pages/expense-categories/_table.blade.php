<!--begin::Table-->
{{ $dataTable->table() }}
<!--end::Table-->

{{-- Inject DataTable scripts --}}
@section('scripts')
    {{ $dataTable->scripts() }}
@endsection
