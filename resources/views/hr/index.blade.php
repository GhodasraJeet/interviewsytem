@extends('layouts.app')
@section('title','Hr Details')
@section('css')

    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">

@endsection

@section('content')

<div class="bg-white w-100 p-2">
<div class="bg-white w-100 p-2">


<form action="" method="post" id="addhrform" class="mb-2">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="hrname">Name</label>
                    <input type="text" id="hrname" class="form-control" name="hrname">                    
                    <span class="text-danger error" data-error="hrname"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="hremail">Email</label>
                    <input type="text" id="hremail" class="form-control" name="hremail"  data-error="hremail">
                    <span class="text-danger error" data-error="hremail"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group" id="hrpasswordedit">
                            <label for="hrpassword">Password</label>
                <input type="password" id="hrpassword" class="form-control" name="hrpassword"  data-error="hrpassword">
                    
                </div>
                <span class="text-danger error" data-error="hrpassword"></span>
        </div>
        <div class="col-md-6">
            <div class="form-group" id="passwordgroup">
                            <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" class="form-control" name="confirm-password"  data-error="confirm-password">
                  
                </div>
                <span class="text-danger error" data-error="confirm-password"></span>
            </div>
    </div>
    <div class="float-right px-1 ml-1" >
        <button type="submit" class="btn btn-primary px-1 ml-1">
            <i class="bx bx-check d-block d-sm-none"></i>
            <span class="d-none d-sm-block"><i class="bx bx-plus"></i>&nbsp;&nbsp;Save </span>
        </button>
    </div>
<input type="hidden" name="hrid" id="hrid" value="">
</form>

</div>
<div class="row">
    <div class="col-md-12 bg-white  ">
        <div class="float-right w-25 mt-2">
            <input type="text" name="searchhr" id="searchhr" class="form-control" placeholder="Search here">
        </div>
        <div class="clearfix"></div>
        @include('hr.hrpagination')
        <button class="btn btn-danger my-2" id="multipledelete">Delete</button>
    </div>
</div>
</div>


</div>

<div class="modal fade text-left" id="deletehrmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" > 
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1">Confirmation</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to delete ?
                </p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" id="hrdeletemodal">
                    @csrf
                <input type="hidden" name="" id="hrdeleteid">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="submit" class="btn btn-danger ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Delete</span>
                </button>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

    <script src="{{asset('js/datatables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
    <script>
        setTimeout(() => { $('.toast').hide(); }, 2000);


    $('.checkbox').on('click',function(){
        if($('.checkbox:checked').length==$('.checkbox').length){
            $('#check_all').prop('checked',true);
        }
        else{
            $('#check_all').prop('checked',false);
        }
    });

    var idsArr=[];
    $('#multipledelete').on('click',function(){
        $('.checkbox:checked').each(function(){
            idsArr.push($(this).attr('data-id'));
        });
        if(idsArr.length==0){
             toastr.info('Select at least one', 'Success Message');
        }
        else
        {
              $('#deletehrmodal').modal('show');
              var strIds=idsArr.push($(this).attr('data-id'));
        }
    });

    $(document).on('submit','#hrdeletemodal',function(e){     
        e.preventDefault();
        var strIds=strIds;
        var strIds=idsArr.join(','); 
         $.ajax({
            url: "{{route('deletemultiplehrs')}}",
            type: "DELETE",
            data:'ids='+strIds,
            success: function(data){
                toastr.success(data.success, 'Success Message');
                $('.checkbox:checked').each(function(){
                    $(this).parents("tr").remove();
                });
                $('#deletehrmodal').modal('hide');
                fetch_hr(current_page);
            }
        });
    });


    var current_page='1';
    function fetch_hr(page='',query='')
    {
        $.ajax({
            url:"{{route('hrsearch')}}",
            method: 'post',
            data:{page:page,search:query},
            success:function(data)
            {
                $('#hrdata').html('');
                $('#hrdata').html(data);
            }
        });
    }

    // Pagination HR
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        current_page=page;
        $('#hidden_page').val(page);    
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_hr(page);
    });

    // Delete HR
    $(document).on('click','.deletehr',function(){
        var hrid=$(this).attr('data-id');
        $('#hrdeleteid').val(hrid);
         $('#hrdeletemodal').attr('id','singledeletemodal');
        $('#deletehrmodal').modal('show');
    });

    $(document).on('submit','#singledeletemodal',function(e){
        e.preventDefault();
        var hrid=$('#hrdeleteid').val();
         $.ajax({
            url: 'hr/'+hrid,
            type: "DELETE",
            success: function(data){
                toastr.success(data.success, 'Success Message');
                $('#deletehrmodal').modal('hide');
                fetch_hr(current_page);
                 $('#singledeletemodal').attr('id','hrdeletemodal');
            }
        });
    });

     // Search Job
    $('#searchhr').on('keyup',function(){
        var query=$(this).val();
        fetch_hr('',query);
    });


    // Add HR
    $(document).on('submit','#addhrform',function(e){
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            url: "{{ route('hr.store')}}",
            method: 'post',
            data: $('#addhrform').serialize(),
            dataType: 'json',
            success: function(data){
                $('#addhrform')[0].reset();
                toastr.success(data.success, 'Success Message');
                fetch_hr();
            },
            error:function(error){
                let errors = error.responseJSON.errors;
                for(let key in errors)
                {
                    let errorDiv = $(`[data-error="${key}"]`);
                    if(errorDiv.length )
                    {
                        errorDiv.text(errors[key][0]);
                    }
                }
            }
        });
    });

    // Edit Job
    $(document).on('click','.edithr',function(){
        $('#hrid,#hrname,#hremail').val('');
        $('.error').html('');
        $('#addhrform').attr('id','updatehrform');
        var hrid=$(this).attr('data-id');
        $.ajax({
            url:'hr/'+hrid,
            method:'GET',
            success:function(data){
                if(data.success){
                    $('#hrid').val(data.success.id);
                    $('#hrname').val(data.success.name);
                    $('#hremail').val(data.success.email);
                }
            }
        });
    });

// update hr
    $(document).on('submit','#updatehrform',function(e){
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            url: "{{ route('updatehr')}}",
            method: 'post',
            data: $('#updatehrform').serialize(),
            dataType: 'json',
            success: function(data){
                $('#updatehrform')[0].reset();
                toastr.success(data.success, 'Success Message');
                fetch_hr(current_page);
                $('#updatehrform').attr('id','addhrform');
            },
            error:function(error){
                let errors = error.responseJSON.errors;
                for(let key in errors)
                {
                    let errorDiv = $(`[data-error="${key}"]`);
                    if(errorDiv.length )
                    {
                        errorDiv.text(errors[key][0]);
                    }
                }
            }
        });
    });

    </script>
@endsection
