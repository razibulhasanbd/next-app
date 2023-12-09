@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.utilityItem.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.utility-items.update", [$utilityItem->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="utility_category_id">{{ trans('cruds.utilityItem.fields.utility_category') }}</label>
                <select class="form-control select2 {{ $errors->has('utility_category') ? 'is-invalid' : '' }}" name="utility_category_id" id="utility_category_id" onchange=utilityCategory(this.value) required>
                    @foreach($utility_categories as $id => $entry)
                        <option value="{{ $id }}" {{ (old('utility_category_id') ? old('utility_category_id') : $utilityItem->utility_category->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('utility_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('utility_category') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.utility_category_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="icon_url">{{ trans('cruds.utilityItem.fields.icon_url') }}</label>
                <input class="form-control {{ $errors->has('icon_url') ? 'is-invalid' : '' }}" type="text" name="icon_url" id="icon_url" value="{{ old('icon_url', $utilityItem->icon_url) }}">
                @if($errors->has('icon_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('icon_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.icon_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="header">{{ trans('cruds.utilityItem.fields.header') }}</label>
                <input class="form-control {{ $errors->has('header') ? 'is-invalid' : '' }}" type="text" name="header" id="header" value="{{ old('header', $utilityItem->header) }}" required>
                @if($errors->has('header'))
                    <div class="invalid-feedback">
                        {{ $errors->first('header') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.header_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="description">{{ trans('cruds.utilityItem.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" required>{{ old('description', $utilityItem->description) }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.description_helper') }}</span>
            </div>
            <div class="form-group" id="download_file_url_div">
                <label for="download_file_url">{{ trans('cruds.utilityItem.fields.download_file_url') }}</label>
                <input class="form-control {{ $errors->has('download_file_url') ? 'is-invalid' : '' }}" type="file" name="download_file_url" id="download_file_url" value="{{ old('download_file_url', $utilityItem->download_file_url) }}">
                @if($errors->has('download_file_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('download_file_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.download_file_url_helper') }}</span>
            </div>
            <div class="form-group" id="youtube_embedded_url_div">
                <label for="youtube_embedded_url">{{ trans('cruds.utilityItem.fields.youtube_embedded_url') }}</label>
                <textarea class="form-control {{ $errors->has('youtube_embedded_url') ? 'is-invalid' : '' }}" name="youtube_embedded_url" id="youtube_embedded_url" onchange="youtubeThumbnail()">{{ old('youtube_embedded_url', $utilityItem->youtube_embedded_url) }}</textarea>
                <div class="mt-2">
                    <label for="youtube_thumbnail_url">{{ trans('cruds.utilityItem.fields.youtube_thumbnail_url') }}</label>
                    <input class="form-control mt-3 {{ $errors->has('youtube_thumbnail_url') ? 'is-invalid' : '' }}" type="text" name="youtube_thumbnail_url" id="youtube_thumbnail_url" value="{{ old('youtube_thumbnail_url', $utilityItem->youtube_thumbnail_url) }}">
                    <img class="mt-3" id="preview_thumbnail" src="{{ old('youtube_thumbnail_url', $utilityItem->youtube_thumbnail_url ) }}" alt="Thumbnail Preview" width="200" >
                </div>
                @if($errors->has('youtube_embedded_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('youtube_embedded_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.youtube_embedded_url_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.utilityItem.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\UtilityItem::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $utilityItem->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="order_value">{{ trans('cruds.utilityItem.fields.order_value') }}</label>
                <input class="form-control {{ $errors->has('order_value') ? 'is-invalid' : '' }}" type="number" name="order_value" id="order_value" value="{{ old('order_value', $utilityItem->order_value) }}" step="1">
                @if($errors->has('order_value'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_value') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.utilityItem.fields.order_value_helper') }}</span>
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
@parent
<script>

$( document ).ready(function() {
    if(document.getElementById('utility_category_id').value == 3){
    $('#youtube_embedded_url_div').show();
    $('#download_file_url_div').hide();
}else{
    $('#download_file_url_div').show();
     $('#youtube_embedded_url_div').hide();
}
});

    function utilityCategory(category){
        if(category == 3){
            $('#youtube_embedded_url_div').show();
            $('#download_file_url_div').hide();
        }else if(category == 1 || category == 2){
            $('#download_file_url_div').show();
            $('#youtube_embedded_url_div').hide();
        }
    }

    function youtubeThumbnail(){
        var youtube_embedded_url = document.getElementById('youtube_embedded_url').value;
        var youtube_thumbnail_preview = document.getElementById('preview_thumbnail');
        var youtube_thumbnail_url = '';

        if(youtube_embedded_url.includes("watch?v=")){
            youtube_thumbnail_url= "https://img.youtube.com/vi/"+youtube_embedded_url.replace("https://www.youtube.com/watch?v=", "")+"/0.jpg";
            youtube_thumbnail_preview.src = youtube_thumbnail_url ? youtube_thumbnail_url : '#';
        }else if( youtube_embedded_url.includes("embed/")){
            youtube_thumbnail_url = "https://img.youtube.com/vi/"+youtube_embedded_url.replace("https://www.youtube.com/embed/", "")+"/0.jpg";
            youtube_thumbnail_preview.src = youtube_thumbnail_url ? youtube_thumbnail_url : '#';
        }else{
            youtube_thumbnail_url = 'url not valid';
            youtube_thumbnail_preview.src = '';
        }
        document.getElementById('youtube_thumbnail_url').value=youtube_thumbnail_url;
    }

    </script>
@endsection
