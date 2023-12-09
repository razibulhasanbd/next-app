@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.plan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.plans.update", [$plan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.plan.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', $plan->type) }}" required>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.plan.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $plan->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.plan.fields.description') }}</label>
                <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $plan->description) }}" required>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="leverage">{{ trans('cruds.plan.fields.leverage') }}</label>
                <input class="form-control {{ $errors->has('leverage') ? 'is-invalid' : '' }}" type="number" name="leverage" id="leverage" value="{{ old('leverage', $plan->leverage) }}" step="1" required>
                @if($errors->has('leverage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('leverage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.leverage_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="starting_balance">{{ trans('cruds.plan.fields.starting_balance') }}</label>
                <input class="form-control {{ $errors->has('startingBalance') ? 'is-invalid' : '' }}" type="number" name="startingBalance" id="startingBalance" value="{{ old('startingBalance', $plan->startingBalance) }}" step="1" required>
                @if($errors->has('startingBalance'))
                    <div class="invalid-feedback">
                        {{ $errors->first('startingBalance') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.starting_balance_helper') }}</span>
            </div>
            {{-- <div class="form-group">
                <label class="required" for="upgrade_threshold">{{ trans('cruds.plan.fields.upgrade_threshold') }}</label>
                <input class="form-control {{ $errors->has('upgrade_threshold') ? 'is-invalid' : '' }}" type="number" name="upgrade_threshold" id="upgrade_threshold" value="{{ old('upgrade_threshold', $plan->upgrade_threshold) }}" step="0.01" required>
                @if($errors->has('upgrade_threshold'))
                    <div class="invalid-feedback">
                        {{ $errors->first('upgrade_threshold') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.upgrade_threshold_helper') }}</span>
            </div> --}}
            {{-- <div class="form-group">
                <label class="required">{{ trans('cruds.plan.fields.liquidate_friday') }}</label>
                @foreach(App\Models\Plan::LIQUIDATE_FRIDAY_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('liquidate_friday') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="liquidate_friday_{{ $key }}" name="liquidate_friday" value="{{ $key }}" {{ old('liquidate_friday', $plan->liquidate_friday) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="liquidate_friday_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('liquidate_friday'))
                    <div class="invalid-feedback">
                        {{ $errors->first('liquidate_friday') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.liquidate_friday_helper') }}</span>
            </div> --}}
            <div class="form-group">
                <label class="required" for="package_id">{{ trans('cruds.plan.fields.package') }}</label>
                <select class="form-control select2 {{ $errors->has('package') ? 'is-invalid' : '' }}" name="package_id" id="package_id" required>
                    @foreach($packages as $id => $entry)
                        <option value="{{ $id }}" {{ (old('package_id') ? old('package_id') : $plan->package->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('package'))
                    <div class="invalid-feedback">
                        {{ $errors->first('package') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.package_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="server_id">{{ trans('cruds.plan.fields.server') }}</label>
                <select class="form-control select2 {{ $errors->has('server') ? 'is-invalid' : '' }}" name="server_id" id="server_id" required>
                    @foreach($servers as $id => $entry)
                        <option value="{{ $id }}" {{ (old('server_id') ? old('server_id') : $plan->server->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('server'))
                    <div class="invalid-feedback">
                        {{ $errors->first('server') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.server_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="duration">{{ trans('cruds.plan.fields.duration') }}</label>
                <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', $plan->duration) }}" required>
                @if($errors->has('duration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('duration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.duration_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="next_plan">{{ trans('cruds.plan.fields.next_plan') }}</label>
                <input class="form-control {{ $errors->has('next_plan') ? 'is-invalid' : '' }}" type="number" name="next_plan" id="next_plan" value="{{ old('next_plan', $plan->next_plan) }}" step="1" required>
                @if($errors->has('next_plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('next_plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.next_plan_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('new_account_on_next_plan') ? 'is-invalid' : '' }}">
                    <input class="form-check-input" type="checkbox" name="new_account_on_next_plan" id="new_account_on_next_plan" value="1" {{ $plan->new_account_on_next_plan || old('new_account_on_next_plan', 0) === 1 ? 'checked' : '' }}>
                    <label class="required form-check-label" for="new_account_on_next_plan">{{ trans('cruds.plan.fields.new_account_on_next_plan') }}</label>
                </div>
                @if($errors->has('new_account_on_next_plan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('new_account_on_next_plan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.plan.fields.new_account_on_next_plan_helper') }}</span>
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