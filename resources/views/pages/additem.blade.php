@extends('layouts.app', [
    'class' => 'sidebar-mini',
    'namePage' => 'Add Food Item',
    'activePage' => 'additem',
    'activeNav' => '',
])

@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{__("Add Food Item")}}</h5>
                </div>
                <div class="card-body">
            <form method="post" action="{{ route('pages.additem') }}" enctype="multipart/form-data">
            @csrf
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->has('name'))
            <div class="alert alert-danger">{{ $errors->first('name') }}</div>
            @endif

            <div class="row">
                <div class="col-md-7 pr-1">
                    <div class="form-group">
                        <label>{{__("Food Name")}}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7 pr-1">
                    <div class="form-group">
                        <label>{{__("Price")}}</label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-md-7 pr-1">
                <div class="form-group">
                    <label>{{__("Food Photo")}}</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="photo" class="custom-file-input" id="photo" required>
                            <label class="custom-file-label" for="photo">Choose file</label>
                        </div>
                    </div>
                    @include('alerts.feedback', ['field' => 'photo'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 pr-1">
                <div class="form-group">
                    <label>{{__("Number of Items")}}</label>
                    <input type="number" name="number_of_items" class="form-control" value="{{ old('number_of_items') }}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 pr-1">
                <div class="form-group">
                    <label>{{__("Food Category")}}</label>
                    <select name="category" class="form-control" required>
                        <option value="" disabled selected>Select category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-round">{{__('Add Item')}}</button>
        </div>
    </form>
    </div>
            </div>
        </div>


        <div class="col-md-4">
    <div class="card">
        <div class="card-header">
            <h5 class="title">Food Items</h5>
        </div>
        <div class="card-body">
            <table class="table">
                <thead class="text-primary">
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($foods as $food)
                    @php $encryptedId = encrypt($food->id); @endphp
                    <tr>
                        <td>
                            <h6>{{ $food->name }}</h6>
                            <p>Price: Php {{ $food->price }}</p>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $encryptedId }}" data-id="{{ $encryptedId }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form id="deleteForm{{ $encryptedId }}" class="deleteForm" action="{{ route('additem.delete', ['id' => $encryptedId]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger delete-btn" data-id="{{ $encryptedId }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $encryptedId }}" tabindex="-1" role="dialog" aria-labelledby="editModal{{ $encryptedId }}Label" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModal{{ $encryptedId }}Label">Edit Food Item</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Form to edit food item -->
                                    <form method="POST" action="{{ route('additem.update', ['id' => $encryptedId]) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <!-- Input fields for name, price, and photo -->
                                        <div class="form-group">
                                            <label for="editName{{ $encryptedId }}">Name</label>
                                            <input type="text" class="form-control" id="editName{{ $encryptedId }}" name="name" value="{{ $food->name }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="editPrice{{ $encryptedId }}">Price</label>
                                            <input type="number" class="form-control" id="editPrice{{ $encryptedId }}" name="price" value="{{ $food->price }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="editNumberOfItems{{ $encryptedId }}">Number of Items</label>
                                            <input type="number" class="form-control" id="editNumberOfItems{{ $encryptedId }}" name="number_of_items" value="{{ $food->number_of_items }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="editPhoto{{ $encryptedId }}">Photo</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="editPhoto{{ $encryptedId }}" name="photo">
                                                    <label class="custom-file-label" for="editPhoto{{ $encryptedId }}" style="width: 450px;">Choose file</label>
                                                </div>
                                            </div>
                                            @if($food->photo)
                                            <label class="label">Current Image</label>
                                            <img src="{{ asset('storage/' . $food->photo) }}" alt="Food Photo" style="max-width: 200px;">
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $foods->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteForms = document.querySelectorAll('.deleteForm');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this item!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@if(Session::has('delete_message'))
    <script>
        Swal.fire(
            'Deleted!',
            'Food item has been deleted.',
            'success'
        );
    </script>
@endif

<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = '';
        fileName = e.target.value.split('\\').pop();
        var label = document.querySelector('.custom-file-label');
        label.innerHTML = fileName;
    });
</script>
<script>
    document.getElementById('editPhoto{{$food->id}}').addEventListener('change', function (e) {
        var fileName = e.target.files[0].name;
        var label = document.querySelector('#editPhoto{{$food->id}}').nextElementSibling;
        label.innerHTML = fileName;
    });
</script>

@endsection

