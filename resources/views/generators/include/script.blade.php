@push('js')
    <script src="{{ asset('mazer') }}/vendors/jquery/jquery.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/fontawesome/all.min.js"></script>
    <script src="{{ asset('mazer') }}/vendors/sweetalert2/sweetalert2.all.min.js"></script>

    @include('generators.include.js.create-js')

    @include('generators.include.js.function-js')
@endpush
