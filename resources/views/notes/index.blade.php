@extends('layouts.app')
@section('title','All Notes')
@section('css')

    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
    <style>
        .checkbox-input{cursor: pointer}
    </style>
@endsection
@section('content')




<div class="bg-white w-100 p-2">

<div class="bg-white w-100 p-2">

    <form method="post" id="noteformsingle" class="mb-2">
        <div class="row align-items-start">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="notetitle" id="singlenotetitle" class="form-control">
                    <span class="text-danger error" data-error="notetitle"></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                        <label>Description</label>
                            <textarea  class="form-control" id="notedescription" name="description"></textarea>
                           
                            <span class="text-danger error" data-error="description"></span>
                    </div>
            </div>
            <div class="col-md-4">
                <input type="hidden" name="noteid" id="noteid">
                    <div class="checkbox">
                        <input type="checkbox" name="favouritenote" class="checkbox-input" id="favouritenote">
                        <label for="favouritenote">Favourite</label>
                    </div>
            </div>
        </div>
        <div class="float-right px-1 ml-1">
            <button type="submit" class="btn btn-primary px-1 ml-1">
                <i class="bx bx-check d-block d-sm-none"></i>
                <span class="d-none d-sm-block"><i class="bx bx-plus"></i>&nbsp;&nbsp;Save </span>
            </button>
        </div>  
    </form>

</div>
  



<div class="row">
    <div class="col-md-12 bg-white">
        <div class="float-right w-25 mt-2">
            <input type="text" name="searchnote" id="searchnote" class="form-control" placeholder="Search here">
        </div>
        <div class="clearfix"></div>
        @include('notes.notepagination')
        <button class="btn btn-danger my-2" id="multipledelete">Delete</button>
    </div>
</div>
</div>


<div class="modal fade text-left" id="deletenotemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true" > 
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
                    <form action="" method="post" id="notedeletemodal">
                    @csrf
                    <input type="hidden" name="deletenot" id="deletenot">
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
    $(document).ready(function(){
        setTimeout(() => { $('.toast').hide(); }, 2000);
        $('.error').html('');
// Add Or Remove Favourite on Note
$('.favourite').on('change',function(){
    $('#success-message').html('');
        var current='';
        var notesid=$(this).attr('data-id');
        var checkbox=$(this);
        if($(this).is(":checked")){
        current='check';
        }
        else
        {
        current='uncheck';
        }
        $.ajax({
        url: "{{ route('note.favourite')}}",
        method: 'post',
        data: {"noteid":notesid,"current":current},
        success: function(data){
        if(data.success){
        $('#success-message').append();
        toastr.success(data.success, 'Success Message');
        setTimeout(() => { $('.toast').hide(); }, 2000);

        }
        if(data.danger){
        $('#success-message').append();

        setTimeout(() => { $('.toast').hide(); }, 2000);

        }
        }
    });
});
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
            $('#deletenotemodal').modal('show');
             idsArr.push($(this).attr('data-id'));

        }
    });

    $(document).on('submit','#notedeletemodal',function(e){
        
      e.preventDefault();
       
          var strIds=idsArr.join(','); 
         $.ajax({
                url: "{{route('deletemultiplenotes')}}",
                type: "DELETE",
                data:'ids='+strIds,
                success: function(data){
                    toastr.success(data.success, 'Success Message');
                    $('.checkbox:checked').each(function(){
                        $(this).parents("tr").remove();
                    });
                    $('#deletenotemodal').modal('hide');
                    fetch_note(current_page);
                }
            });
    });

    var current_page='1';
    function fetch_note(page='',query='')
    {
        $.ajax({
            url:"{{route('notesearch')}}",
            method: 'post',
            data:{page:page,search:query},
            success:function(data)
            {
                $('#notedata').html('');
                $('#notedata').html(data);
            }
        });
    }

     // Pagination Note
    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        current_page=page;
        $('#hidden_page').val(page);
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        fetch_note(page);
    });

     // Delete Note
    $(document).on('click','#deletenote',function(){
        var noteid=$(this).attr('data-id');
        $('#deletenot').val(noteid);
        $('#notedeletemodal').attr('id','singledeletemodal');
        $('#deletenotemodal').modal('show');
    });

    $(document).on('submit','#singledeletemodal',function(e){
        e.preventDefault();
        var noteid=$('#deletenot').val();
         $.ajax({
            url: 'notes/'+noteid,
            type: "DELETE",
            success: function(data){
                toastr.success(data.success, 'Success Message');
                   $('#deletenotemodal').modal('hide');
                fetch_note(current_page);
                 $('#singledeletemodal').attr('id','notedeletemodal');
            }
        });
    });

    // Edit Note
    $(document).on('click','.editnote',function(){
        $('#singlenotetitle,#notedescription').val('');
        $('.error').html('');
        var noteid=$(this).attr('data-id');
        // alert(noteid);
        $.ajax({
            url:'notes/'+noteid,
            method:'GET',
            success:function(data){
                if(data.success){
                    $('#noteformsingle').attr('id','updatenoteform');
                    $('#noteid').val(data.success.id);
                    $('#singlenotetitle').val(data.success.title);
                    $('#notedescription').val(data.success.description);
                    if(data.success.favourite==1)
                    {
                        $('#favouritenote').prop('checked',true);
                    }
                    else{
                        $('#favouritenote').prop('checked',false);
                    }
                }

            }
        });
    });


    // Add Note
    $(document).on('submit','#noteformsingle',function(e){
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            url: "{{ route('notes.store')}}",
            method: 'post',
            data: $('#noteformsingle').serialize(),
            dataType: 'json',
            success: function(data){
                console.log(data);
                if(data.danger){
                    toastr.success(data.danger, 'Danger Message');
                }
                if(data.success){
                    $('#noteformsingle')[0].reset();
                    toastr.success(data.success, 'Success Message');
                    fetch_note();
                }
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

    // Search Job
    $('#searchnote').on('keyup',function(){
        var query=$(this).val();
        fetch_note('',query);
    });

    // Update Job
    $(document).on('submit','#updatenoteform',function(e){
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            url:"{{route('updatenote')}}",
            method:'post',
            data:$('#updatenoteform').serialize(),
            dataType: 'json',
            success:function(data){
                if(data.success)
                {
                    $('#updatenoteform')[0].reset();
                    toastr.success(data.success, 'Success Message');
                    fetch_note(current_page);
                }
                if(data.danger)
                {
                    toastr.success(data.danger, 'Success Message');
                }
                $('#updatenoteform').attr('id','noteformsingle');
            },
            error:function(error)
            {
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


});
    </script>
@endsection