@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.coupon.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.coupons.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.coupon.fields.type') }}</label>
                {{-- <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="number" name="type" id="type" value="{{ old('type', '0') }}" step="1" required> --}}
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.coupon.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.coupon.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', '') }}" required>
                @if($errors->has('code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.coupon.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="expiry_date">{{ trans('cruds.coupon.fields.expiry_date') }}</label>
                <input class="form-control datetime {{ $errors->has('expiry_date') ? 'is-invalid' : '' }}" type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" required>
                @if($errors->has('expiry_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('expiry_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.expiry_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_redemption">{{ trans('cruds.coupon.fields.max_redemption') }}</label>
                <input class="form-control {{ $errors->has('max_redemption') ? 'is-invalid' : '' }}" type="number" name="max_redemption" id="max_redemption" value="{{ old('max_redemption', '') }}" step="1">
                @if($errors->has('max_redemption'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_redemption') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.max_redemption_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_redemption_per_user">{{ trans('cruds.coupon.fields.max_redemption_per_user') }}</label>
                <input class="form-control {{ $errors->has('max_redemption_per_user') ? 'is-invalid' : '' }}" type="number" name="max_redemption_per_user" id="max_redemption_per_user" value="{{ old('max_redemption_per_user', '') }}" step="1">
                @if($errors->has('max_redemption_per_user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_redemption_per_user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.coupon.fields.max_redemption_per_user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="amount">{{ trans('cruds.coupon.fields.amount') }}</label>
                <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="float" name="amount" id="amount" value="{{ old('amount', '') }}">
                @if($errors->has('amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </div>
                @endif
                {{-- <span class="help-block">{{ trans('cruds.coupon.fields.amount') }}</span> --}}
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

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.coupons.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $coupon->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection
