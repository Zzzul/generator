<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ isset($detailPurchase) ? $detailPurchase->name : old('name') }}" placeholder="{{ __('Name') }}" required />
            @error('name')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
    </div>
 	<div class="col-md-6">
        <div class="form-group">
			<label for="detail-purchases" class="form-label">{{ __('Detail Purchases') }}</label>
			<input class="form-control @error('detail_purchases') is-invalid @enderror" name="detail_purchases" list="detailPurchaseOptions" id="detail-purchases" placeholder="{{ __('Type to search...') }}" value="{{ isset($detailPurchase) && $detailPurchase->detail_purchases ? $detailPurchase->detail_purchases : old('detail_purchases') }}"  required>
			<datalist id="detailPurchaseOptions">

                @foreach ($detailSales as $detailSale)
                            <option value="{{ $detailSale->id }}" {{ isset($detailPurchase) && $detailPurchase->detail_purchases == $detailSale->id ? 'selected' : (old('detail_purchases') == $detailSale->id ? 'selected' : '') }}>
                                {{ $detailSale->id }}
                            </option>
                        @endforeach
			</datalist>
            @error('detail_purchases')
                <span class="text-danger">
                    {{ $message }}
                </span>
            @enderror
        </div>
	</div>
</div>
