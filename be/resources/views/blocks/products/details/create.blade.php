







@extends('master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Add Product Detail for {{ $product->name }}
                </header>

                <form action="{{route('products.details.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="size_id">Size</label>
                            <select name="size_id" class="form-control" required>
                                @foreach ($sizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="color_id">Color</label>
                            <select name="color_id" class="form-control" required>
                                @foreach ($colors as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="default_price">Default Price</label>
                            <input type="number" step="0.01" name="default_price" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="discount_price">Discount Price</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{  route('products.show', $product->id) }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
