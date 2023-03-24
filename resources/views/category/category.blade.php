@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Category</h2>
                </div>
                <div class="pull-right mb-2">
                    <a class="btn btn-success" onClick="addCategoryModal()" href="javascript:void(0)">Add Category</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card-body">
            <table class="table table-bordered" id="categoriesID">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Category Name</th>
                        <th>Category Details</th>
                        {{-- <th>Category Image</th> --}}
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- boostrap product model -->
    <div class="modal fade" id="category-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="CategoryModal"></h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="categoryForm" name="categoryForm" class="form-horizontal"
                        method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Category Name:</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    placeholder="Category Name" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Category Details:</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="category_details" name="category_details"
                                    placeholder="Category Details" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Category Image:</label>
                            <div class="col-sm-12">
                                <input type="file" class="form-control" id="category_image" name="category"
                                    placeholder="category Image">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Get Records using Ajax 
            $('#categoriesID').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('category.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'category_details',
                        name: 'category_details'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false
                    },
                ],
                order: [
                    [0, 'desc']
                ]
            });
        });

        //View Category Popup
        function addCategoryModal() {
            $('#categoryForm').trigger("reset");
            $('#CategoryModal').html("Add Category");
            $('#category-modal').modal('show');
            $('#id').val('');
        }

        //Add Records
        $('#categoryForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('category.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#category-modal").modal('hide');
                    var oTable = $('#categoriesID').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                },
                error: function(err) {
                    alert("error")
                    console.log("err :", err);
                }
            });
        });

        //Edit Records
        $(document).ready(function() {
            $(document).on("click", "a.editCategory", function(e) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    url: "{{ route('category.edit') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#categoryModal').html("Edit product");
                        $('#category-modal').modal('show');
                        $('#id').val(res.id);
                        $('#category_name').val(res.category_name);
                        $('#category_details').val(res.category_details);
                    },
                    error: function(err) {
                        console.log("err :", err);
                    }
                });
            });
        });

        //Delete Records
        $(document).ready(function() {
            $(document).on("click", "a.deleteCategory", function(e) {
                var dId = $(this).attr('id');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('category.delete') }}",
                    data: {
                        id: dId
                    },
                    dataType: 'json',
                    success: (data) => {
                        var oTable = $('#categoriesID').dataTable();
                        oTable.fnDraw(false);
                    },
                    error: function(err) {
                        console.log("err :", err);
                    }
                });

            });
        });
    </script>
@endsection
