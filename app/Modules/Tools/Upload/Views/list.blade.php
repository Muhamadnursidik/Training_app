@if ($files->count() > 0)
<div class="row">
    <div class="col-md-12">
        <ul class="list-style-none ml-0 pl-1">
            @if ($showLabel)
            <li class="my-2 border-bottom pb-3 list-group-item">
                <span class="font-weight-medium text-dark"><i class="bx bx-download bx-xs" aria-hidden="true"></i>
                    Attachment:</span>
            </li>
            @endif
            @php
                $no = 1;
                $jml = 0;
            @endphp
            @foreach ($files as $file)
                <li class="my-1 attachment_{{ $no.'_'.$file->module }}" id="attachment_{{ $no.'_'.$file->module }}">
                    <span>
                        <div class="btn-group" role="group" aria-label="attachment">
                            @if (isset($action) && in_array('download', $action))
                                <a id="download_{{ $no.'_'.$file->module }}" href="{{ route('tools.tools.upload.download', ['id' => encrypt($file->id), 'module' => $file->module]) }}"
                                    class="download">
                                    {!! $file->oriname !!}
                                </a>
                            @else
                                {!! $file->oriname !!}
                            @endif

                            @if (isset($action) && in_array('delete', $action))
                                <a data-target=".attachment_{{ $no.'_'.$file->module }}" rel="tooltip"
                                    title="Delete attachment file"
                                    href="{{ route('tools.tools.upload.destroy', ['id' => encrypt($file->id), 'module' => $file->module]) }}"
                                    type="button" class="text-danger delete">
                                    <i class="bx bx-trash bx-xs" aria-hidden="true"></i>
                                </a>
                            @endif
                        </div>
                    </span>
                </li>
                @php
                    $no++;
                    $jml++;
                @endphp
            @endforeach
        </ul>
    </div>
    {!! Form::hidden('jml_attachment', $jml, ['class' => 'jml_attachment_'.$source_id, 'id' => 'jml_attachment']) !!}
</div>
@endif
@push('custom-scripts')
    <script>
        $('.delete').on('click', (e) => {
            e.preventDefault();

            var myajax = $(e.target).parent();
            var target = $(e.target).parent().attr('data-target');
            var jml_attachment = $(".jml_attachment_{{ $source_id }}").val();

            $(myajax).myAjax({
                url : $(e.target).parent().attr('href'),
                success: function(data) {
                    $(target).remove();
                    $(".jml_attachment_{{ $source_id }}").val(jml_attachment - 1);
                }
            }).delete({
                confirm: {
                    title: 'Apakah anda yakin?',
                    text: 'File yang dihapus tidak dapat dikembalikan'
                }
            });
        })

        $('.download').on('click', (e) => {
            e.preventDefault();
            var id = $(e.target).attr('id');
            var target = $('#' + id).attr('href');

            window.open(target, '_blank');
        })

        $(function() {
            $('[rel="bs-tooltip"]').tooltip();
        })
    </script>
@endpush
