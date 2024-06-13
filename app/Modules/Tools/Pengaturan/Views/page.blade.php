<div class="row">
    <div class="col">
        {{ Form::open(['id' => 'my-form', 'route' => [$module . '.update', encrypt($data->id)], 'method' => 'put', 'autocomplete' => 'off']) }}
        <div class="card">
            <div class="card-header fw-bold">Pengaturan {{ ucfirst(strtolower($tab)) }}</div>
            <div class="card-body">
                <div class="row">
                    @php
                        $i = 0;
                    @endphp
                    @foreach (json_decode($data->config) as $item)
                        <div class="col-md-12">
                            <div class="form-group row mb-1">
                                <label for="name" class="col-sm-5 col-form-label">{{ (isset($item->label) && $item->label != '') ? $item->label : $item->key }}</label>
                                <div class="col-sm-7">
                                    {!! Form::hidden("config[$i][key]", $item->key) !!}
                                    {!! Form::hidden("config[$i][label]", isset($item->label) ? $item->label : '') !!}
                                    {!! Form::hidden("config[$i][tipe]", isset($item->tipe) ? $item->tipe : 'string') !!}
                                    {!! Form::hidden("config[$i][options]", isset($item->options) ? $item->options : '') !!}
                                    {!! Form::hidden("config[$i][fileallow]", isset($item->fileallow) ? $item->fileallow : '') !!}
                                    @if (isset($item->tipe))
                                        @switch($item->tipe)
                                            @case('text')
                                                {!! Form::textarea("config[$i][value]", $item->value, [
                                                    'class' => 'form-control',
                                                ]) !!}
                                                @break
                                            @case('string')
                                                {!! Form::text("config[$i][value]", $item->value, [
                                                    'class' => 'form-control',
                                                ]) !!}
                                                @break
                                            @case('boolean')
                                                {!! Form::hidden("config[$i][value]", 'false') !!}
                                                <div class="cb-dynamic-label custom-control custom-toggle custom-toggle-sm mb-1 mt-1 form-check form-switch form-check form-check-inline">
                                                    <input {{ (isset($item->value) && $item->value == 'true') ? 'checked="checked"' : '' }} type="checkbox" id="custom_url"
                                                        name="config[{{ $i }}][value]" value="true" class="custom-control-input form-check-input"
                                                        data-text-on="{{ __('Ya') }}" data-text-off="{{ __('Tidak') }}">
                                                    <label class="custom-control-label text-muted" for="custom_url"></label>
                                                </div>
                                                @break
                                            @case('dropdown')
                                                @php
                                                    $options = [];
                                                    $explode = isset($item->options) ? explode(';',$item->options) : [];
                                                    foreach ($explode as $value) {
                                                        $options[$value] = $value;
                                                    }
                                                @endphp
                                                {!! Form::select("config[$i][value]"
                                                    , $options
                                                    , $item->value, [
                                                        'class' => 'form-select',
                                                    ]) !!}
                                                @break
                                            @case('upload')
                                                <input class="form-control" name="config[{{ $i }}][value]" type="file" accept="{{ isset($item->fileallow) ? $item->fileallow : '' }}">
                                                @break
                                            @default
                                                {!! Form::text("config[$i][value]", $item->value, [
                                                    'class' => 'form-control ',
                                                ]) !!}
                                        @endswitch
                                    @else
                                        {!! Form::text("config[$i][value]", $item->value, [
                                            'class' => 'form-control',
                                        ]) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @php
                        $i++;
                    @endphp
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script type="text/javascript">
    $(function(){
        initPage();
        $('form#my-form').submit(function(e){
            e.preventDefault();
            $(this).myAjax({
                waitMe: '.modal-content',
                success: function (data) {
                    console.log(data);
                    tabsChart('{{ $tab }}_tab')
                }
            }).submit();
        });
    })
</script>