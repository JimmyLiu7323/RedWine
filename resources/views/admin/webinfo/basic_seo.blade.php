@extends('admin.layouts.main')
@section('content')
<style type="text/css">
.preview_image{max-width:100px}
</style>
@if(\Session::has('maintain_message'))
<script>
    @if(\Session::has('maintain_message_fail'))
Swal.fire({
    type:'error',
    title:'Oops...',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_fail') !!}",
})
    @elseif(\Session::has('maintain_message_success'))
Swal.fire({
    type:'success',
    title:'成功',
    customClass:'swal-wide',
    text:"{!! \Session::get('maintain_message_success') !!}",
})        
    @endif               
</script>
@endif
<div class="row">
    <div class="col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">SEO大綱設定</div>
            </div>
            <div class="card-body">
                <form class="form form-horizontal" id='product_form' method="POST" enctype="multipart/form-data">
                    <div class="section">
                        <div class="section-body">
                            @foreach($seoData as $seoKey=>$seoValue)
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{$seo_Title[$seoKey]}}</label>
                                <div class="col-md-9">
                                    @if($seoValue[0]=='text')
                                    <input type="text" class="form-control" value="{{$seoValue[1]}}" name="{{$seoKey}}"/>
                                    @elseif($seoValue[0]=='textarea')
                                    <textarea class="form-control" name="{{$seoKey}}" rows="10">{!!$seoValue[1]!!}</textarea>
                                    @elseif($seoValue[0]=='file')
                                    <input type="file" class="form-control" name="{{$seoKey}}" id="{{$seoKey}}" accept=".jpg,.jpeg,.png,.gif"/>
                                    <img @if($seoValue[1]!='') src={!!asset("uploads/".$seoValue[1])!!} @endif id="preview_{{$seoKey}}" class="preview_image"/>
                                    @elseif($seoValue[0]=='date')
                                    <input type="date" class="form-control" name="{{$seoKey}}" value="{{$seoValue[1]}}" />
                                    @else
                                        @if(strpos($seoValue[0],'readonly')!==false)
                                            @php $cutValue=explode("_",$seoValue[0]); @endphp
                                    <input type='text' class='form-control' name={{$seoKey}} readonly="true" value="{{end($cutValue)}}" />
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
                    <div class="form-footer">
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type='submit' class='btn btn-primary'>儲存</button>
                            </div>
                        </div>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
const img_preview_arr=JSON.parse('{!!$img_preview!!}');
function readURL(input){                
    if(input.files&&input.files[0]){
        const reader=new FileReader();
        reader.onload=function(e){
            const inputId=$(input).attr('id');
            $('#preview_'+inputId).attr('src',e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
img_preview_arr.forEach(fileId=>{
    $("#"+fileId).change(function(){
        readURL(this);
    })
})
</script>
@endsection