    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="form-group mb-3">
                <x-form.input required="1" name="name" :value="$product->name" class="form-control-lg" label="Product Name" />
            </div>
            <div class="form-group mb-3">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">Select One</option>
                    @foreach(\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}" @if ($category->id == old('category_id', $product->category_id)) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mb-3">
                <x-form.textarea label="Description" name="description" :value="$product->description" />
            </div>

            <div class="form-row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <x-form.input type="number" step="0.1" name="price" :value="$product->price" label="Price" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <x-form.input type="number" step="0.1" name="compare_price" :value="$product->compare_price" label="Compare Price" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <x-form.input type="number" step="0.1" name="cost" :value="$product->cost" label="Cost" />
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="sku">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control @error('sku') is-invalid @enderror">
                        @error('sku')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="barcode">Barcode</label>
                        <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="form-control @error('barcode') is-invalid @enderror">
                        @error('barcode')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="quantity">Qauntity</label>
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" class="form-control @error('quantity') is-invalid @enderror">
                        @error('quantity')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="availability">Availability</label>
                        <select id="availability" name="availability" class="form-control @error('availability') is-invalid @enderror">
                            <option value="">Select One</option>
                            @foreach($availabilities as $key => $availability)
                            <option value="{{ $key }}" @if ($key == old('availability', $product->availability)) selected @endif>{{ $availability }}</option>
                            @endforeach
                        </select>
                        @error('availability')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                    <option value="">Select One</option>
                    @foreach($status_options as $key => $status)
                    <option value="{{ $key }}" @if ($key == old('status', $product->status)) selected @endif>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="image">Thumbnail</label>
                <div class="mb-2">
                    @if ($product->image)
                    <img id="thumbnail" src="{{ Storage::disk('public')->url($product->image) }}" height="150">
                    @else
                    <img id="thumbnail" src="{{ asset('images/blank.png') }}" height="150">
                    @endif
                </div>
                <input type="file" style="display: none;" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
                <a href="{{ route('dashboard.categories.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.getElementById('thumbnail').addEventListener('click', function(e) {
        document.getElementById('image').click();
    });

    document.getElementById('image').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            document.getElementById('thumbnail').src = URL.createObjectURL(this.files[0]);
        }
    });

</script>
@endpush