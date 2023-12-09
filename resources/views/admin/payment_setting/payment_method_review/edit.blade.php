@extends('layouts.admin')
@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-2">
                <section class="panel panel-default">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            {{ trans('cruds.payment_method.fields.waiting_approval_update') }} - {{ $payment_method->name ?? '' }}
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route("admin.payment-method-review.update", $payment_method) }}" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")

                                <div class="form-group">
                                    <label for="status">{{ trans('cruds.payment_method.fields.status') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="is_sent_for_review" id="status">
                                        <option value="">Select {{ trans('cruds.payment_method.fields.waiting_approval_update') }}</option>
                                        @foreach($paymentMethodApprovalStatus as $key => $module)
                                            <option  value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <button type="submit" class="btn btn-success">{{ trans('cruds.payment_method.fields.waiting_approval_update') }}</button>
                            </form>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
<style>

</style>
@section('scripts')
    @parent
    <script>

    </script>
@endsection
