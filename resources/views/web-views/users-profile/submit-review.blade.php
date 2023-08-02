@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('submit_a_review'))

@section('content')

<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 mt-2 rtl"
     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
    <div class="row">
        <!-- Sidebar-->
    @include('web-views.partials._profile-aside')
        <section class="col-lg-9  col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="__ml-20">{{\App\CPU\translate('submit_a_review')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('review.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('rating')}}</label>
                                <select class="form-control" name="rating">
                                    <option value="1">{{\App\CPU\translate('1')}}</option>
                                    <option value="2">{{\App\CPU\translate('2')}}</option>
                                    <option value="3">{{\App\CPU\translate('3')}}</option>
                                    <option value="4">{{\App\CPU\translate('4')}}</option>
                                    <option value="5">{{\App\CPU\translate('5')}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('comment')}}</label>
                                <input name="product_id" value="{{$order_details->product_id}}" hidden>
                                <input name="order_id" value="{{$order_details->order_id}}" hidden>
                                <textarea class="form-control" name="comment"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">{{\App\CPU\translate('attachment')}}</label>
                                <div class="row coba"></div>
                                <div class="mt-1 text-info">{{\App\CPU\translate('File type: jpg, jpeg, png. Maximum size: 2MB')}}</div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a href="{{ URL::previous() }}" class="btn btn-secondary">{{\App\CPU\translate('back')}}</a>

                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/front-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'fileUpload[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                placeholderImage: {
                    image: '{{asset('public/assets/front-end/img/image-place-holder.png')}}',
                    width: '100%'
                },
                dropFileLabel: "{{\App\CPU\translate('drop_here')}}",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
