<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Asset | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')

        <main class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Add a new asset</h1>
                <a href="{{ route('asset.index') }}" class="btn btn-outline-secondary">Back to assets</a>
            </div>

            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('asset.store') }}">
                @csrf
                <div class="row g-4">
                    <div class="col-12 col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3">Device information</h2>
                                <p class="text-body-secondary small">Fields marked with * are required.</p>
                                <div class="row g-3">
                                    <div class="col-12 col-md-8">
                                        <label for="name" class="form-label">Name *</label>
                                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="255" placeholder="My MacBook Pro" required>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="asset_type_id" class="form-label">Type *</label>
                                        <select id="asset_type_id" name="asset_type_id" class="form-select" required>
                                            <option value="">Choose a type</option>
                                            @foreach ($assetTypes as $type)
                                                <option value="{{ $type->id }}" @selected(old('asset_type_id') == $type->id)>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="manufacturer" class="form-label">Manufacturer</label>
                                        <input id="manufacturer" name="manufacturer" type="text" class="form-control" value="{{ old('manufacturer') }}" maxlength="255" placeholder="Apple">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="model" class="form-label">Model</label>
                                        <input id="model" name="model" type="text" class="form-control" value="{{ old('model') }}" maxlength="255" placeholder="MacBook Pro 13-inch">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="model_number" class="form-label">Model number</label>
                                        <input id="model_number" name="model_number" type="text" class="form-control" value="{{ old('model_number') }}" maxlength="255" placeholder="A1502">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="serial_number" class="form-label">Serial number</label>
                                        <input id="serial_number" name="serial_number" type="text" class="form-control" value="{{ old('serial_number') }}" maxlength="255">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="status" class="form-label">Status *</label>
                                        <select id="status" name="status" class="form-select" required>
                                            @foreach (['active' => 'Active', 'stored' => 'Stored', 'maintenance' => 'Under maintenance', 'broken' => 'Broken', 'retired' => 'Retired', 'sold' => 'Sold', 'lost' => 'Lost'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="condition" class="form-label">Current condition *</label>
                                        <select id="condition" name="condition" class="form-select" required>
                                            @foreach (['excellent' => 'Excellent', 'good' => 'Good', 'fair' => 'Fair / Visible wear', 'poor' => 'Poor / Heavily worn', 'broken' => 'Broken / Nonfunctional'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('condition', 'excellent') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="location" class="form-label">Location</label>
                                        <input id="location" name="location" type="text" class="form-control" value="{{ old('location') }}" maxlength="255" placeholder="Home office">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3">Purchase and ownership</h2>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label for="acquisition_method" class="form-label">How did you acquire it?</label>
                                        <select id="acquisition_method" name="acquisition_method" class="form-select">
                                            <option value="">Unknown</option>
                                            @foreach (['purchased' => 'Purchased', 'gifted' => 'Gifted', 'inherited' => 'Inherited', 'other' => 'Other'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('acquisition_method') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="acquisition_condition" class="form-label">Condition when acquired</label>
                                        <select id="acquisition_condition" name="acquisition_condition" class="form-select">
                                            <option value="">Unknown</option>
                                            @foreach (['new' => 'New', 'used' => 'Used', 'refurbished' => 'Refurbished'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('acquisition_condition') === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="purchase_date" class="form-label">Purchase date</label>
                                        <input id="purchase_date" name="purchase_date" type="date" class="form-control" value="{{ old('purchase_date') }}">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="warranty_expires_at" class="form-label">Warranty expires</label>
                                        <input id="warranty_expires_at" name="warranty_expires_at" type="date" class="form-control" value="{{ old('warranty_expires_at') }}">
                                    </div>
                                    <div class="col-8">
                                        <label for="purchase_price" class="form-label">Purchase price</label>
                                        <input id="purchase_price" name="purchase_price" type="number" class="form-control" value="{{ old('purchase_price') }}" min="0" max="9999999999.99" step="0.01" placeholder="0.00">
                                    </div>
                                    <div class="col-4">
                                        <label for="currency" class="form-label">Currency</label>
                                        <select id="currency" name="currency" class="form-select">
                                            <option value="">Choose</option>
                                            @foreach (['EUR' => 'EUR (€)', 'HUF' => 'HUF (Ft)', 'USD' => 'USD ($)', 'GBP' => 'GBP (£)', 'CHF' => 'CHF'] as $value => $label)
                                                <option value="{{ $value }}" @selected(old('currency') == $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="seller" class="form-label">Seller</label>
                                        <input id="seller" name="seller" type="text" class="form-control" value="{{ old('seller') }}" maxlength="255" placeholder="Store or seller name">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3">Additional information</h2>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Private notes</label>
                                    <textarea id="description" name="description" class="form-control" rows="4" placeholder="Anything else you want to remember">{{ old('description') }}</textarea>
                                </div>
                                <label class="form-label">Specifications</label>
                                <p class="text-body-secondary small">Add a specification name and its value, for example Memory and 8 GB.</p>
                                <div id="specificationRows">
                                    @foreach (old('specification_rows', [['key' => '', 'value' => '']]) as $index => $specification)
                                        <div class="row g-2 mb-2 specification-row">
                                            <div class="col-5">
                                                <input type="text" name="specification_rows[{{ $index }}][key]" class="form-control" value="{{ $specification['key'] ?? '' }}" placeholder="Memory" aria-label="Specification name" maxlength="100">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" name="specification_rows[{{ $index }}][value]" class="form-control" value="{{ $specification['value'] ?? '' }}" placeholder="8 GB" aria-label="Specification value" maxlength="255">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-outline-danger w-100" data-remove-spec aria-label="Remove specification">&times;</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button id="addSpecification" type="button" class="btn btn-outline-secondary btn-sm">Add specification</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3">Tags</h2>
                                @forelse ($tags as $tag)
                                    <div class="form-check mb-2">
                                        <input id="tag_{{ $tag->id }}" name="tags[]" type="checkbox" class="form-check-input" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', [])))>
                                        <label for="tag_{{ $tag->id }}" class="form-check-label">{{ $tag->name }}</label>
                                    </div>
                                @empty
                                    <p class="text-body-secondary mb-0">You haven't created any tags yet. You can add them later.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 mb-3">Visibility</h2>
                                <input type="hidden" name="is_public" value="0">
                                <div class="form-check">
                                    <input id="is_public" name="is_public" type="checkbox" class="form-check-input" value="1" @checked(old('is_public', false))>
                                    <label for="is_public" class="form-check-label">Make this asset public</label>
                                </div>
                                <p class="text-body-secondary small mt-2 mb-0">Public assets appear in Explore and can be viewed through a shared link. Leave unchecked to keep this asset private.</p>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create asset</button>
                            <a href="{{ route('asset.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </main>

        <script>
            const specificationRows = document.getElementById('specificationRows');
            let specificationIndex = specificationRows.children.length;

            document.getElementById('addSpecification').addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 specification-row';
                row.innerHTML = `
                    <div class="col-5">
                        <input type="text" name="specification_rows[${specificationIndex}][key]" class="form-control" placeholder="Memory" aria-label="Specification name" maxlength="100">
                    </div>
                    <div class="col-5">
                        <input type="text" name="specification_rows[${specificationIndex}][value]" class="form-control" placeholder="8 GB" aria-label="Specification value" maxlength="255">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-outline-danger w-100" data-remove-spec aria-label="Remove specification">&times;</button>
                    </div>
                `;
                specificationRows.appendChild(row);
                row.querySelector('input').focus();
                specificationIndex++;
            });

            specificationRows.addEventListener('click', (event) => {
                const button = event.target.closest('[data-remove-spec]');
                if (button) button.closest('.specification-row').remove();
            });
        </script>
        @include('partials.footer')
    </body>
</html>