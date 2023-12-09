@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.typeform.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.typeforms.update", [$typeform->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="payments_for">{{ trans('cruds.typeform.fields.payments_for') }}</label>
                <input class="form-control {{ $errors->has('payments_for') ? 'is-invalid' : '' }}" type="text" name="payments_for" id="payments_for" value="{{ old('payments_for', $typeform->payments_for) }}" required>
                @if($errors->has('payments_for'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payments_for') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.payments_for_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="funding_package">{{ trans('cruds.typeform.fields.funding_package') }}</label>
                <input class="form-control {{ $errors->has('funding_package') ? 'is-invalid' : '' }}" type="text" name="funding_package" id="funding_package" value="{{ old('funding_package', $typeform->funding_package) }}">
                @if($errors->has('funding_package'))
                    <div class="invalid-feedback">
                        {{ $errors->first('funding_package') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.funding_package_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="funding_amount">{{ trans('cruds.typeform.fields.funding_amount') }}</label>
                <input class="form-control {{ $errors->has('funding_amount') ? 'is-invalid' : '' }}" type="text" name="funding_amount" id="funding_amount" value="{{ old('funding_amount', $typeform->funding_amount) }}">
                @if($errors->has('funding_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('funding_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.funding_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="coupon_code">{{ trans('cruds.typeform.fields.coupon_code') }}</label>
                <input class="form-control {{ $errors->has('coupon_code') ? 'is-invalid' : '' }}" type="text" name="coupon_code" id="coupon_code" value="{{ old('coupon_code', $typeform->coupon_code) }}">
                @if($errors->has('coupon_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('coupon_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.coupon_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="payment_method">{{ trans('cruds.typeform.fields.payment_method') }}</label>
                <input class="form-control {{ $errors->has('payment_method') ? 'is-invalid' : '' }}" type="text" name="payment_method" id="payment_method" value="{{ old('payment_method', $typeform->payment_method) }}" required>
                @if($errors->has('payment_method'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_method') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.payment_method_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="payment_proof">{{ trans('cruds.typeform.fields.payment_proof') }}</label>
                <div class="needsclick dropzone {{ $errors->has('payment_proof') ? 'is-invalid' : '' }}" id="payment_proof-dropzone">
                </div>
                @if($errors->has('payment_proof'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_proof') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.payment_proof_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="paid_amount">{{ trans('cruds.typeform.fields.paid_amount') }}</label>
                <input class="form-control {{ $errors->has('paid_amount') ? 'is-invalid' : '' }}" type="text" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $typeform->paid_amount) }}" required>
                @if($errors->has('paid_amount'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid_amount') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.paid_amount_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.typeform.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $typeform->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.typeform.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', $typeform->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.typeform.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', $typeform->country) }}" required>
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="login">{{ trans('cruds.typeform.fields.login') }}</label>
                <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="text" name="login" id="login" value="{{ old('login', $typeform->login) }}">
                @if($errors->has('login'))
                    <div class="invalid-feedback">
                        {{ $errors->first('login') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.login_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.typeform.fields.payment_verification') }}</label>
                <select class="form-control {{ $errors->has('payment_verification') ? 'is-invalid' : '' }}" name="payment_verification" id="payment_verification">
                    <option value disabled {{ old('payment_verification', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Typeform::PAYMENT_VERIFICATION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_verification', $typeform->payment_verification) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_verification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_verification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.payment_verification_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="approved_at">{{ trans('cruds.typeform.fields.approved_at') }}</label>
                <input class="form-control datetime {{ $errors->has('approved_at') ? 'is-invalid' : '' }}" type="text" name="approved_at" id="approved_at" value="{{ old('approved_at', $typeform->approved_at) }}">
                @if($errors->has('approved_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('approved_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.approved_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="denied_at">{{ trans('cruds.typeform.fields.denied_at') }}</label>
                <input class="form-control datetime {{ $errors->has('denied_at') ? 'is-invalid' : '' }}" type="text" name="denied_at" id="denied_at" value="{{ old('denied_at', $typeform->denied_at) }}">
                @if($errors->has('denied_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('denied_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.denied_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="remarks">{{ trans('cruds.typeform.fields.remarks') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{!! old('remarks', $typeform->remarks) !!}</textarea>
                @if($errors->has('remarks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('remarks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.typeform.fields.remarks_helper') }}</span>
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
    Dropzone.options.paymentProofDropzone = {
    url: '{{ route('admin.typeforms.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="payment_proof"]').remove()
      $('form').append('<input type="hidden" name="payment_proof" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="payment_proof"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($typeform) && $typeform->payment_proof)
      var file = {!! json_encode($typeform->payment_proof) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="payment_proof" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
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
                xhr.open('POST', '{{ route('admin.typeforms.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $typeform->id ?? 0 }}');
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