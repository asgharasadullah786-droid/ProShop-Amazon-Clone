@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Your Store</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('vendor.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label>Store Description</label>
                            <textarea name="store_description" class="form-control" rows="5">{{ $user->store_description }}</textarea>
                            <small class="text-muted">Tell customers about your store</small>
                        </div>
                        
                        <div class="mb-3">
                            <label>Store Logo/Avatar</label>
                            @if($user->avatar)
                            <div class="mb-2">
                                <img src="{{ asset($user->avatar) }}" style="width: 100px; height: 100px; border-radius: 50%;">
                            </div>
                            @endif
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Store</button>
                        <a href="{{ route('vendor.storefront', $user->id) }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection