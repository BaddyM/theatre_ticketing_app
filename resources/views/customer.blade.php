@extends("common")

@section("title")
    {{ $data->seat_id }}
@endsection

@section("body")
    <div class="container pt-3">
        <div class="form-floating mb-3">
            <input type="text" class="form-control text-capitalize" placeholder="XXXX" value="{{ $data->name }}" disabled>
            <label>Customer Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" placeholder="XXXX" value="{{ $data->phone }}" disabled>
            <label>Phone</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control text-capitalize" placeholder="XXXX" value="{{ $data->movie }}" disabled>
            <label>Movie</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" placeholder="XXXX" value="{{ $data->amount }}" disabled>
            <label>Amount</label>
        </div>
        <div class="mb-3">
            <label class="text-white">Status</label><br>
            <span class="badge bg-success">{{ $data->status }}</span>
        </div>
    </div>
@endsection