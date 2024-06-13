<title>{{ $file->oriname }} </title>
<div class="ratio ratio-1x1">
    <iframe src="{{ Storage::url($file->module . DIRECTORY_SEPARATOR . $file->name) }}" title="testPdf" style="border: 0; width: 100%; height: 100%"></iframe>
</div>