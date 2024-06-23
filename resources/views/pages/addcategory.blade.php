@extends('layouts.app', [
    'class' => 'sidebar-mini',
    'namePage' => 'Add Food Category',
    'activePage' => 'addcategory',
    'activeNav' => '',
])


@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
            <div class="card card-user">
                            <div class="image">
                                <img src="{{asset('assets')}}/img/foodcateg.jpg" alt="...">
                            </div>
                        </div>
                <div class="card-header">
               
                    <h5 class="title">{{__("Add Food Category")}}</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('pages.addcategory') }}" enctype="multipart/form-data">
                        @csrf
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if($errors->has('name'))
                            <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                        @endif
                        <div class="row">
                            <div class="col-md-7 pr-1">
                                <div class="form-group">
                                    <label>{{__("Food Category Name")}}</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-round">{{__('Add Category')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Food Categories</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th> <!-- Added action column -->
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
    <tr id="category-{{ $category->id }}">
        <td>{{ $category->name }}</td>
        <td class="align-middle">
            <div class="btn-group align-items-center" role="group">
            <form class="delete-form" action="{{ route('addcategory.destroy', encrypt($category->id)) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger btn-sm delete-category" data-encrypted-id="{{ encrypt($category->id) }}">
        <i class="fa fa-trash"></i>
    </button>
</form>

                <button type="button" class="btn btn-primary btn-sm view-food" data-toggle="modal" data-target="#foodModal{{ $category->id }}">
                    View
                </button>
            </div>
        </td>
    </tr>
@endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@foreach($categories as $category)
<div class="modal fade" id="foodModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="foodModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="foodModalLabel">Food Names for {{ $category->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    @foreach($category->foods as $food)
                        <li>
                            <strong>Name:</strong> {{ $food->name }}<br>
                            <strong>Price:</strong> {{ $food->price }}<br>
                            <strong>Image:</strong><br>
                            <img src="{{ asset('storage/' . $food->photo) }}" alt="{{ $food->name }}" style="max-width: 200px; max-height: 200px;">
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.delete-category');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const form = button.closest('.delete-form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this category!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, submit the form
                        form.submit();
                    }
                });
            });
        });

        @if(Session::has('delete_message'))
            Swal.fire(
                'Deleted!',
                'Food Category has been deleted.',
                'success'
            );
        @endif
    });

    document.addEventListener("DOMContentLoaded", function() {
    
    const viewButtons = document.querySelectorAll('.view-food');

    viewButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            const categoryId = button.dataset.id;
            const modalId = `#foodModal${categoryId}`;
            $(modalId).modal('show');
        });
    });
    
});
</script>
@endsection