@extends('layouts.admin')
@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 offset-2">
                <section class="panel panel-default">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            {{ trans('cruds.payment_method.fields.update_country_category') }} - {{ $country->name ?? '' }}
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route("admin.payment_method.country_category.swap",$country) }}" enctype="multipart/form-data">
                                @csrf
                                @method("PUT")

                                <div class="form-group">
                                    <label for="country_category">{{ trans('cruds.payment_method.fields.country_category') }} <span style="color: red;">*</span></label>
                                    <select class="form-control" name="country_category" id="country_category">
                                        <option value="">{{ trans('cruds.payment_method.fields.select_country_category') }}</option>
                                        @foreach($paymentCountryCategory as $key => $module)
                                            <option {{ $country->country_category == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">{{ trans('cruds.payment_method.fields.update_country_category') }}</button>
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
