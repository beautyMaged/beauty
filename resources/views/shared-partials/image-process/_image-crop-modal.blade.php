<style>
    input[type="file"] {
        display: none;
    }

    .custom-file-upload {
        margin-left: 38%;
        border: 1px solid #ccc;
        display: inline-block;
        padding: 6px 12px;
        cursor: pointer;
    }
</style>

@if(!isset($width))
    @php($width=516)
@endif

@if(!isset($margin_left))
    @php($margin_left='0%')
@endif

<div class="modal fade" id="{{$modal_id}}" tabindex="-1" role="dialog" aria-labelledby=""
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: {{$width+66}}px;margin-left: {{$margin_left}}">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="">{{str_replace('-',' ',$modal_id)}}</h5>
            </div>
            <div class="modal-body">

                <div class="alert alert-block alert-success" id="img-suc-{{$modal_id}}"
                     style="display: none;">
                    <i class="ace-icon fa fa-check green"></i>
                    <strong class="green">
                        {{\App\CPU\translate('Image uploaded successfully')}}.
                    </strong>
                </div>

                <div class="alert alert-block alert-danger" id="img-err-{{$modal_id}}"
                     style="display: none;">
                    <strong class="red">
                        {{\App\CPU\translate('Error , Something went wrong')}} !
                    </strong>
                </div>

                <div class="row" id="show-images-{{$modal_id}}">
                    @include('shared-partials.image-process._show-images',['folder'=>str_replace('-','_',$modal_id)])
                </div>

                <form>
                    <div class="form-group" style="display: none" id="crop-{{$modal_id}}">
                        <div id="upload-image-div-{{$modal_id}}"></div>
                    </div>
                    <div class="form-group" id="select-img-{{$modal_id}}">
                        <label for="image-set-{{$modal_id}}" class="custom-file-upload">
                            {{\App\CPU\translate('Choose Image')}} <i class="fa fa-plus-circle"></i>
                        </label>
                        <input type="file" name="image" onchange="cropView('{{$modal_id}}')"
                               id="image-set-{{$modal_id}}" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{\App\CPU\translate('Close')}}
                </button>
                <button type="button" class="btn btn--primary btn-upload-image-{{$modal_id}}" style="display: none">
                    {{\App\CPU\translate('Add')}}
                </button>
            </div>
        </div>
    </div>
</div>
