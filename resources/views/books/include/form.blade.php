<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" list="nameOptions" id="name"
                placeholder="{{ __('Type to search...') }}" required>
            <datalist id="nameOptions">
                <option value="Sains"
                    {{ isset($book) && $book->name == 'Sains' ? 'selected' : (old('name') == 'Sains' ? 'selected' : '') }}>
                    Sains</option>
                <option value="Fiction"
                    {{ isset($book) && $book->name == 'Fiction' ? 'selected' : (old('name') == 'Fiction' ? 'selected' : '') }}>
                    Fiction</option>
                <option value="Horror"
                    {{ isset($book) && $book->name == 'Horror' ? 'selected' : (old('name') == 'Horror' ? 'selected' : '') }}>
                    Horror</option>
                <option value="Comedy"
                    {{ isset($book) && $book->name == 'Comedy' ? 'selected' : (old('name') == 'Comedy' ? 'selected' : '') }}>
                    Comedy</option>
            </datalist>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input class="form-control @error('title') is-invalid @enderror" name="title" list="titleOptions"
                id="title" placeholder="{{ __('Type to search...') }}" required>
            <datalist id="titleOptions">

                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ isset($book) && $book->title == $user->id ? 'selected' : (old('title') == $user->id ? 'selected' : '') }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </datalist>
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
