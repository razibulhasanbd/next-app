@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.accountStatusLog.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.account-status-logs.update", [$accountStatusLog->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="account_id">{{ trans('cruds.accountStatusLog.fields.account') }}</label>
                <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id" id="account_id" required>
                    @foreach($accounts as $id => $entry)
                        <option value="{{ $id }}" {{ (old('account_id') ? old('account_id') : $accountStatusLog->account->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('account'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountStatusLog.fields.account_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="data">{{ trans('cruds.accountStatusLog.fields.data') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('data') ? 'is-invalid' : '' }}" name="data" id="data">{!! old('data', $accountStatusLog->data) !!}</textarea>
                @if($errors->has('data'))
                    <div class="invalid-feedback">
                        {{ $errors->first('data') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountStatusLog.fields.data_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="old_status_id">{{ trans('cruds.accountStatusLog.fields.old_status') }}</label>
                <select class="form-control select2 {{ $errors->has('old_status') ? 'is-invalid' : '' }}" name="old_status_id" id="old_status_id">
                    @foreach($old_statuses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('old_status_id') ? old('old_status_id') : $accountStatusLog->old_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('old_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('old_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountStatusLog.fields.old_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="new_status_id">{{ trans('cruds.accountStatusLog.fields.new_status') }}</label>
                <select class="form-control select2 {{ $errors->has('new_status') ? 'is-invalid' : '' }}" name="new_status_id" id="new_status_id" required>
                    @foreach($new_statuses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('new_status_id') ? old('new_status_id') : $accountStatusLog->new_status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('new_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('new_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.accountStatusLog.fields.new_status_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.account-status-logs.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $accountStatusLog->id ?? 0 }}');
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