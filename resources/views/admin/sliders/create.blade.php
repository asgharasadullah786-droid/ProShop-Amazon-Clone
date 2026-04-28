@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Add New Slider</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('sliders.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Title *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Subtitle</label>
                    <textarea name="subtitle" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label>Image URL *</label>
                    <input type="url" name="image" class="form-control" required placeholder="https://via.placeholder.com/1920x600">
                </div>
                <div class="mb-3">
                    <label>Button Text</label>
                    <input type="text" name="button_text" class="form-control" placeholder="Shop Now">
                </div>
                <div class="mb-3">
                    <label>Button Link</label>
                    <input type="url" name="button_link" class="form-control" placeholder="/products">
                </div>
                <div class="mb-3">
                    <label>Order (Display Sequence)</label>
                    <input type="number" name="order" class="form-control" value="0">
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create Slider</button>
                <a href="{{ route('sliders.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection