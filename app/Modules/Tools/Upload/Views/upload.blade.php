@push('vendor-style')
    <style>
        .btn-file{overflow:hidden;position:relative;vertical-align:middle}
        .btn-file>input{border-radius:0;cursor:pointer;direction:ltr;filter:alpha(opacity=0);font-size:23px;height:100%;margin:0;opacity:0;position:absolute;right:0;top:0;width:100%}
        .fileinput .input-group-addon{background:#fff;border:none;border-bottom:1px solid #e9ecef;margin-bottom:1px}
        .fileinput .form-control{cursor:text;display:inline-block;margin-bottom:0;padding-bottom:5px;padding-top:7px;vertical-align:middle}
        .fileinput .thumbnail{display:inline-block;margin-bottom:5px;overflow:hidden;text-align:center;vertical-align:middle}
        .fileinput .thumbnail>img{max-height:100%}.fileinput .btn{vertical-align:middle}
        .fileinput-exists .fileinput-new,.fileinput-new .fileinput-exists{display:none}
        .fileinput-inline .fileinput-controls{display:inline}
        .fileinput-filename{display:inline-block;overflow:hidden;vertical-align:middle}
        .form-control .fileinput-filename{vertical-align:bottom}
        .fileinput.input-group>*{position:relative;z-index:2}
        .fileinput.input-group>.btn-file{z-index:1}
    </style>
@endpush
<div class="form-group row p-0 m-1">
    <label for="formFileMultiple" class="col-form-label col-md-2">Attachment File{!! isset($mandatory) ? '<sup class="text-danger">*</sup>' : '' !!}</label>
    <div class="col-lg-10">
        <input class="form-control " name="files[]" 
                type="file" id="formFileMultiple" multiple="" accept="{!! config('data.upload_allowed') !!}">
        <p><sup>File upload : {!! config('data.upload_allowed') !!}</sup></p>
        <div id="error_files"></div>

        {!! App\Helpers\Common::attachment_list(['source_id' => isset($source_id) ? $source_id : '', 'module' => isset($module) ? $module : '', 'action' => isset($action) ? $action : ['delete','download']]) !!}
    </div>
</div>
