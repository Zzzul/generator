<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ isset($product) ? $product->name : old('name') }}" placeholder="{{ __('Name') }}" required />
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">{{ __('Email') }}</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ isset($product) ? $product->email : old('email') }}" placeholder="{{ __('Email') }}" required />
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    @isset($product)
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-1 text-center">
                    <div class="avatar avatar-xl">
                        @if ($product->photo == null)
                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($product->photo))) }}&s=500" alt="photo">
                        @else
                            <img src="{{ asset('uploads/photos/' . $product->photo) }}" alt="Photo">
                        @endif
                    </div>
                </div>

                <div class="col-md-5 me-0 pe-0">
                    <div class="form-group">
                        <label for="photo">{{ __('Photo') }}</label>
                        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                                id="photo">

                        @error('photo')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="form-group">
                <label for="photo">{{ __('Photo') }}</label>
                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" id="photo" required>

                @error('photo')
                    <div class="invalid-feedback">
                        <i class="bx bx-radio-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endisset
    @isset($product)
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-1 text-center">
                    <div class="avatar avatar-xl">
                        @if ($product->document == null)
                            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($product->document))) }}&s=500" alt="document">
                        @else
                            <img src="{{ asset('uploads/documents/' . $product->document) }}" alt="Document">
                        @endif
                    </div>
                </div>

                <div class="col-md-5 me-0 pe-0">
                    <div class="form-group">
                        <label for="document">{{ __('Document') }}</label>
                        <input type="file" name="document" class="form-control @error('document') is-invalid @enderror"
                                id="document">

                        @error('document')
                            <div class="invalid-feedback">
                                <i class="bx bx-radio-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="form-group">
                <label for="document">{{ __('Document') }}</label>
                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" id="document" required>

                @error('document')
                    <div class="invalid-feedback">
                        <i class="bx bx-radio-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    @endisset
</div>