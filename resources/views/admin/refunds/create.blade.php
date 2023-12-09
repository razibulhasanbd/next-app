@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Refund Request
    </div>

    @foreach(['success', 'warning', 'info', 'message'] as $alert)
     @if (Session::has($alert))
        <div class="alert alert-{{$alert}}" role="alert">{{Session::get($alert)}}</div>
     @endif
    @endforeach

    <div class="card-body">
        <form method="POST" action="{{ route("admin.refunds.request") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="amount">Amount</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('url', '') }}" step="any" required>
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="comment">Comment</label>
                <textarea class="form-control" name="comment"required></textarea>
            
                @if($errors->has('comment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.mtServer.fields.login_helper') }}</span>
            </div>

            <input type="hidden" name="order_id" value="{{ $order->id }}">
                    
            <div class="form-group">
                <button class="btn btn-danger" class="save" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $(document).on('submit', 'form', function() {
            $('button').attr('disabled', 'disabled');
        });
    });
    </script>
@endsection
