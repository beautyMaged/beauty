@extends('layouts.front-end.app')

@section('title', \App\CPU\translate('OTP_verification'))


@section('content')
    <div class="container py-4 py-lg-5 my-4 __inline-8">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <h2 class="h3 mb-4">{{\App\CPU\translate('provide_your_otp_and_proceed')}}?</h2>
                <div class="card py-2 mt-4">
                    <form class="card-body needs-validation" action="{{route('customer.auth.otp-verification')}}"
                          method="post">
                        @csrf
                        <div class="form-group">
                            <label>{{\App\CPU\translate('Enter your OTP')}}</label>
                            <div id="divOuter">
                                <div id="divInner">
                                    <input id="partitioned" class="form-control" name="otp" type="text" maxlength="4" />
                                </div>
                            </div>
                        </div>
                        <button class="btn btn--primary" type="submit">{{\App\CPU\translate('proceed')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var obj = document.getElementById('partitioned');
        obj.addEventListener('keydown', stopCarret);
        obj.addEventListener('keyup', stopCarret);

        function stopCarret() {
            if (obj.value.length > 3){
                setCaretPosition(obj, 3);
            }
        }

        function setCaretPosition(elem, caretPos) {
            if(elem != null) {
                if(elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.move('character', caretPos);
                    range.select();
                }
                else {
                    if(elem.selectionStart) {
                        elem.focus();
                        elem.setSelectionRange(caretPos, caretPos);
                    }
                    else
                        elem.focus();
                }
            }
        }
    </script>
@endpush
