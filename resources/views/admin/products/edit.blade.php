@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.update", [$product->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="business_model_id">{{ trans('cruds.product.fields.business_model') }}</label>
                <select class="form-control select2 {{ $errors->has('business_model') ? 'is-invalid' : '' }}" name="business_model_id" id="business_model_id" required>
                    @foreach($business_models as $id => $entry)
                        <option value="{{ $id }}" {{ (old('business_model_id') ? old('business_model_id') : $product->business_model->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('business_model'))
                    <div class="invalid-feedback">
                        {{ $errors->first('business_model') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.business_model_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="model_varient_id">{{ trans('cruds.product.fields.model_varient') }}</label>
                <select class="form-control select2 {{ $errors->has('model_varient') ? 'is-invalid' : '' }}" name="model_varient_id" id="model_varient_id" required>
                    @foreach($model_varients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('model_varient_id') ? old('model_varient_id') : $product->model_varient->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('model_varient'))
                    <div class="invalid-feedback">
                        {{ $errors->first('model_varient') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.model_varient_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="plan_id">{{ trans('cruds.product.fields.plan') }}</label>
                <select class="form-control select2 {{ $errors->has('plan') ? 'is-invalid' : '' }}" name="plan_id" id="plan_id" required>
                    @foreach($plans as $id => $entry)
                        <option value="{{ $id }}" {{ (old('plan_id') ? old('plan_id') : $product->plan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.plan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="buy_price">{{ trans('cruds.product.fields.buy_price') }}</label>
                <input class="form-control {{ $errors->has('buy_price') ? 'is-invalid' : '' }}" type="number" name="buy_price" id="buy_price" value="{{ old('buy_price', $product->buy_price) }}" step="0.01" required>
                @if($errors->has('buy_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('buy_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.buy_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="topup_price">{{ trans('cruds.product.fields.topup_price') }}</label>
                <input class="form-control {{ $errors->has('topup_price') ? 'is-invalid' : '' }}" type="number" name="topup_price" id="topup_price" value="{{ old('topup_price', $product->topup_price) }}" step="0.01" required>
                @if($errors->has('topup_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('topup_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.topup_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="reset_price">{{ trans('cruds.product.fields.reset_price') }}</label>
                <input class="form-control {{ $errors->has('reset_price') ? 'is-invalid' : '' }}" type="number" name="reset_price" id="reset_price" value="{{ old('reset_price', $product->reset_price) }}" step="0.01" required>
                @if($errors->has('reset_price'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reset_price') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.reset_price_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.product.fields.status') }}</label>
                @foreach(App\Models\Product::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $product->status) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection