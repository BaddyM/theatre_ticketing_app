@extends('common')

@section('title')
    Home
@endsection

@section('body')
    <div class="center_container row justify-content-center">
        @foreach ($seats as $seat)
            <div class="seat_empty seat_no" id="seat_no_{{ $seat->seat_no }}" data-seat_no="{{ $seat->seat_no }}">
                <p class="mb-0 fw-bold text-center h4">{{ $seat->seat_no }}</p>
                <p class="mb-0 fw-bold text-center h5" id="booked_{{ $seat->seat_no }}"></p>
            </div>
        @endforeach
    </div>{{-- Customer  Information --}}

    <div class="modal fade" id="customerModal" tabindex="-1" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content rounded-0">
                <form id="add_customer_data_form" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Customer Booking
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="seat_id">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" placeholder="Add Customer Name" name="name"
                                required>
                            <label for="">Customer Name <strong class="text-danger">*</strong></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="Add Customer Number" name="phone"
                                required>
                            <label for="">Customer Number <strong class="text-danger">*</strong></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" placeholder="Add Amount" name="amount" required>
                            <label for="">Amount <strong class="text-danger">*</strong></label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select text-capitalize" name="movie" required>
                                <option value="">Select a Movie</option>
                                @php
                                    $movies = [
                                        'the nun',
                                        'terminator genesis',
                                        'ant man',
                                        'the oval',
                                        'small man',
                                        'spectre',
                                        'the stranger',
                                        'the north man',
                                        'canary black',
                                        'sharper',
                                        'anyone but you',
                                    ];
                                @endphp
                                @foreach ($movies as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                            <label for="">Movie <strong class="text-danger">*</strong></label>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between btn-collection">
                        <button type="submit" class="btn btn-primary px-4 fw-bold" id="save_customer_btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>{{-- Book for the customer --}}

    <div class="modal fade" id="bookedModal" tabindex="-1" data-bs-keyboard="false" role="dialog"
        aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content rounded-0">
                <div class="p-3">
                    <div class="d-flex justify-content-center">
                        <div class="col-md-5 mb-2" id="qrcode">

                        </div>
                    </div>
                    <button class="btn btn-danger fw-bold" id="clear_seat_btn">Clear</button>
                </div>
            </div>
        </div>
    </div>{{-- Show customer qrcode --}}
@endsection

@push('scripts')
    <script>
        $(window).on("load", function() {
            $.ajax({
                type: "POST",
                url: "{{ route('customer.data') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $.each(response.payment, function(k, v) {
                        var seat_no = v.seat_id;
                        $(`#seat_no_${seat_no}`).removeClass("seat_empty").addClass(
                            "seat_booked");
                        $(`#booked_${seat_no}`).text("Booked");
                    });
                },
                error: function() {

                }
            });
        });

        $(document).ready(function() {
            $(document).on("click", ".seat_empty", function() {
                var seat_no = $(this).data("seat_no");
                //Display the modal
                $("#customerModal").modal("show");
                $("input[name='seat_id']").val(seat_no);
                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.data') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $.each(response.payment, function(k, v) {
                            var seat_no = v.seat_id;
                            $(`#seat_no_${seat_no}`).removeClass("seat_empty").addClass(
                                "seat_booked");
                            $(`#booked_${seat_no}`).text("Booked");
                        });
                    },
                    error: function() {

                    }
                });
            });

            $(document).on("click", ".seat_booked", function() {
                var seat_no = $(this).data("seat_no");
                //Display the modal
                $("#bookedModal").modal("show");
                $("#clear_seat_btn").val(seat_no);
                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.qrcode') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        seat_no: seat_no
                    },
                    success: function(response) {
                        $("#qrcode").empty();
                        var qrcode = new QRCode(
                            "qrcode", {
                                text: `${response.link}`, // you can set your QR code text
                                width: 300,
                                height: 300,
                                colorDark: "#000000",
                                colorLight: "#FFFFFF",
                                correctLevel: QRCode.CorrectLevel.M
                            }
                        );
                    },
                    error: function() {

                    }
                });
            });

            $("#customerModal").on("hidden.bs.modal", function() {
                $("#add_customer_data_form")[0].reset();
            });

            $("#add_customer_data_form").on("submit", function(e) {
                e.preventDefault();
                $("#save_customer_btn").removeClass("btn-primary").addClass("btn-secondary").prop(
                    "disabled", true);
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.customer') }}",
                    data: new FormData(this),
                    processData: false,
                    cache: false,
                    contentType: false,
                    success: function(response) {
                        $("#save_customer_btn").addClass("btn-primary").removeClass(
                            "btn-secondary").prop("disabled", false);
                        $("#customerModal").modal("hide");
                        alert(response.message);
                        $(`#seat_no_${response.seat_no}`).removeClass("seat_empty").addClass(
                            "seat_booked");
                        $(`#booked_${response.seat_no}`).text("Booked");
                    },
                    error: function() {

                    }
                });
            });

            $(document).on("click", "#clear_seat_btn", function(e) {
                e.preventDefault();
                var seat_no = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "{{ route('customer.seat.clear') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        seat_no: seat_no
                    },
                    success: function(response) {                    
                    $(`#seat_no_${response.seat_no}`).addClass("seat_empty").removeClass("seat_booked");
                    $(`#booked_${response.seat_no}`).text(null);
                    $("#bookedModal").modal("hide");
                    },
                    error: function() {

                    }
                });

            })
        });
    </script>
@endpush
