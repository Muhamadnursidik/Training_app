<div class="modal-header">
    <h5 class="modal-title">{{ __('Tambah Rencana Project') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

{{ Form::open(['id' => 'form-store', 'route' => $module . '.store', 'method' => 'post', 'autocomplete' => 'off',]) }}
<div class="modal-body">
    <div class="row">
        <!-- Kode Project -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="kode_project" class="form-label required">{{ __('Kode Project') }}</label>
                <input type="text" name="kode_project" id="kode_project" class="form-control" required maxlength="50" placeholder="Masukkan kode project">
            </div>
        </div>

        <!-- Level (readonly, akan di-set otomatis) -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="level" class="form-label">{{ __('Level') }}</label>
                <input type="number" name="level" id="level" class="form-control" value="1" readonly>
                <small class="text-muted">Level akan otomatis disesuaikan berdasarkan parent</small>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="aktivitas" class="form-label required">{{ __('Aktivitas') }}</label>
                <textarea name="aktivitas" id="aktivitas" class="form-control" rows="3" required maxlength="255" placeholder="Deskripsi aktivitas..."></textarea>
            </div>
        </div>

        <!-- Parent -->
        <div class="col-12">
            <div class="form-group mb-3">
                <label for="parent_id" class="form-label">{{ __('Parent Aktivitas') }}</label>
                <select name="parent_id" id="parent_id" class="form-control select2">
                    <option value="">{{ __('- Root Level (Tanpa Parent) -') }}</option>
                    @if(isset($parents) && count($parents) > 0)
                        @foreach($parents as $parent)
                            <option value="{{ $parent['id'] }}" data-level="{{ $parent['level'] }}">
                                {!! $parent['text'] !!}
                            </option>
                        @endforeach
                    @else
                        <option disabled>{{ __('Tidak ada data parent tersedia') }}</option>
                    @endif
                </select>
                <small class="text-muted">Pilih parent untuk membuat sub-aktivitas</small>
            </div>
        </div>

        <!-- Tanggal -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_mulai" class="form-label required">{{ __('Tanggal Mulai') }}</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="tanggal_akhir" class="form-label required">{{ __('Tanggal Akhir') }}</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
            </div>
        </div>

        <!-- Bobot dan Minggu -->
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="bobot" class="form-label">{{ __('Bobot (%)') }}</label>
                <input type="number" name="bobot" id="bobot" class="form-control" min="0" max="100" step="0.01" value="0" placeholder="0-100">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="minggu_ke" class="form-label">{{ __('Minggu Ke') }}</label>
                <input type="number" name="minggu_ke" id="minggu_ke" class="form-control" min="1" max="53" placeholder="Otomatis dari tanggal mulai">
                <small class="text-muted">Kosongkan untuk otomatis</small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
    <button type="submit" class="btn btn-primary">
        <i class="bx bx-save"></i> {{ __('Simpan') }}
    </button>
</div>
{!! Form::close() !!}

@push('plugin-scripts')
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
<script type="text/javascript">
$(function(){
    $('#form-store').on('submit', function(e){
        e.preventDefault();

        var form = $(this);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    $('#modal-md').modal('hide');

                    Swal.fire({
                        title: 'Berhasil!',
                        text: res.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        oTable.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>
@endpush