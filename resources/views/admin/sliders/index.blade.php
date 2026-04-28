@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3>Manage Sliders</h3>
            <a href="{{ route('sliders.create') }}" class="btn btn-primary">+ Add New Slider</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                    <tr>
                        <td>
                            <img src="{{ $slider->image }}" style="height: 50px; width: 100px; object-fit: cover;">
                        </td>
                        <td>{{ $slider->title }}</td>
                        <td>{{ Str::limit($slider->subtitle, 50) }}</td>
                        <td>{{ $slider->order }}</td>
                        <td>
                            <span class="badge bg-{{ $slider->is_active ? 'success' : 'danger' }}">
                                {{ $slider->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('sliders.edit', $slider) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('sliders.destroy', $slider) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this slider?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No sliders found. Create your first slider!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection