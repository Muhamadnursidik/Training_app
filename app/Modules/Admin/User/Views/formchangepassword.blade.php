

@extends((request()->get('password_expired') || request()->get('password_reset')) ? 'layout.modal-no-close' : 'layout.modal')
@section('title',  __('Ganti Password'))

@section('content')
    {{ Form::open(['id' => 'my-form', 'route' => 'admin.savechangepassword', 'method' => 'post', 'autocomplete' => 'off']) }}
    <div class="modal-body pb-2">
        @if(request()->get('password_expired') && !request()->get('password_reset'))
            <div class="alert alert-danger">
                {{ __('passwords.password_expired') }} <br>
            </div>
        @elseif(request()->get('password_expired') && request()->get('password_reset'))
            <div class="alert alert-danger">
                {{ __('passwords.password_reset') }} <br>
            </div>
       @endif
       <div class="form-group mb-1 row">
           <label for="old_password" class="col-sm-4 col-form-label">{{ __('Password Lama') }}<sup class="text-danger">*</sup></label>
           <div class="col-sm-8">
               <div class="position-relative auth-pass-inputgroup">
                   <input type="password" class="form-control " name="old_password" id="old_password">
                   <button
                       class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                       type="button" id="old-password-addon" data-target="#old_password" onclick="showPassword(this)">
                       <i class='bx bx-hide'></i>
                   </button>
               </div>
           </div>
       </div>
        <div class="form-group mb-1 row">
            <label for="password" class="col-sm-4 col-form-label">{{ __('Password Baru') }} 
                <i rel="tooltip" title="Password kombinasi harus mengandung huruf besar[A..Z], huruf kecil [a..z], Angka[0..9] dan Karakter (!@$#%*&^)" class="fas fa-question-circle text-warning"></i><sup class="text-danger">*</sup></label>
            <div class="col-sm-8">
                <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control " name="password" id="password">
                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                        type="button" id="new-password-addon" data-target="#password" onclick="showPassword(this)">
                        <i class='bx bx-hide'></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-group mb-1 row">
            <label for="password_confirmation" class="col-sm-4 col-form-label">{{ __('Konfirm Password') }}<sup class="text-danger">*</sup></label>
            <div class="col-sm-8">
                <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control " name="password_confirmation" id="password_confirmation">
                    <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                        type="button" id="new-password-confirm-addon" data-target="#password_confirmation" onclick="showPassword(this)">
                        <i class='bx bx-hide'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(request()->get('password_reset') || request()->get('password_expired'))
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
        @else 
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
        <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
        @endif
    </div>
    {!! Form::close() !!}
@endsection

@push('plugin-scripts')
<script type="text/javascript">
    function showPassword(el) {
        var passwordField   = $(el).data('target');
        var fieldType       = $(passwordField).attr('type') === 'password' ? 'text' : 'password';
        $(passwordField).attr('type', fieldType);

        var iconHtml = fieldType === 'password' ?
            '<i class="bx bx-hide"></i>' :
            '<i class="bx bx-show"></i>';

        $(el).html(iconHtml);
    }

  $(function(){
    initPage();

    $('form#my-form').submit(function(e){
      e.preventDefault();
      $(this).myAjax({
          waitMe: '.modal-content',
          success: function (data) {
              $('.modal').modal('hide');
          }
      }).submit({confirm:{
          title: 'Yakin Password akan diubah?',
          text: __('Data Password akan disimpan')
        }});
    });

  })
</script>
@endpush
