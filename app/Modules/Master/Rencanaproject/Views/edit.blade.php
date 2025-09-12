<div class="modal-header">
    <h5 class="modal-title">{{ __('Edit Rencana Project') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

{{ Form::model($data, ['route' => ['rencanaproject.update', encrypt($data->id)], 'method' => 'PUT', 'id' => 'form-update', 'class' => 'form-submit']) }}
<div class="modal-body">
    <div class="row">
        <!-- Kode Project -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="kode_project" class="form-label required">{{ __('Kode Project') }}</label>
                {!! Form::text('kode_project', null, ['class' => 'form-control', 'required', 'maxlength' => 50]) !!}
            </div>
        </div>

        <!-- Level -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="level" class="form-label">{{ __('Level') }}</label>
                {!! Form::number('level', null, ['class' => 'form-control', 'readonly']) !!}
                <small class="text-muted">Level akan otomatis disesuaikan berdasarkan parent</small>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="aktivitas" class="form-label required">{{ __('Aktivitas') }}</label>
                {!! Form::textarea('aktivitas', null, ['class' => 'form-control', 'rows' => 3, 'required', 'maxlength' => 255]) !!}
            </div>
        </div>

        <!-- Parent -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="parent_id" class="form-label">{{ __('Parent Aktivitas') }}</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">{{ __('- Root Level (Tanpa Parent) -') }}</option>
                    @if(isset($parents))
                        @foreach($parents as $parent)
                            <option value="{{ $parent['id'] }}" 
                                    data-level="{{ $parent['level'] }}"
                                    {{ $data->parent_id == $parent['id'] ? 'selected' : '' }}>
                                {!! $parent['text'] !!}
                            </option>
                        @endforeach
                    @endif
                </select>
                <small class="text-muted">Pilih parent untuk membuat sub-aktivitas</small>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_mulai" class="form-label required">{{ __('Tanggal Mulai') }}</label>
                {!! Form::date('tanggal_mulai', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_akhir" class="form-label required">{{ __('Tanggal Akhir') }}</label>
                {!! Form::date('tanggal_akhir', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <!-- Bobot dan Minggu -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="bobot" class="form-label">{{ __('Bobot (%)') }}</label>
                {!! Form::number('bobot', null, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => 0.01]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="minggu_ke" class="form-label">{{ __('Minggu Ke') }}</label>
                {!! Form::number('minggu_ke', null, ['class' => 'form-control', 'min' => 1, 'max' => 53]) !!}
                <small class="text-muted">Kosongkan untuk otomatis</small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save"></i> {{ __('Update') }}
    </button>
</div>
{!! Form::close() !!}

<script>
$(document).ready(function() {
    // Initialize Select2 for parent dropdown
    $('#parent_id').select2({
        dropdownParent: $('#modal-md'),
        placeholder: '- Root Level (Tanpa Parent) -',
        allowClear: true
    });

    // Update level when parent is selected
    $('#parent_id').on('change', function() {
        var selectedOption = $(this).find(':selected');
        var parentLevel = selectedOption.data('level') || 0;
        var newLevel = parentLevel + 1;
        $('#level').val(newLevel);
    });

    // Auto-calculate minggu_ke when tanggal_mulai changes
    $('#tanggal_mulai').on('change', function() {
        if ($(this).val() && $('#minggu_ke').val() == '') {
            var date = new Date($(this).val());
            var weekNumber = getWeekNumber(date);
            $('#minggu_ke').val(weekNumber);
        }
    });

    // Validate date range
    $('#tanggal_akhir').on('change', function() {
        var startDate = $('#tanggal_mulai').val();
        var endDate = $(this).val();
        
        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            alert('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai');
            $(this).val('');
        }
    });

    function getWeekNumber(date) {
        var d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        var dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
        return Math.ceil((((d - yearStart) / 86400000) + 1)/7);
    }
});
</script>