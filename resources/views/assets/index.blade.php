<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assets | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @php
            $query = request()->query('q', '');
            $query = is_string($query) ? $query : '';

            $sort = request()->query('sort', 'newest');
            $sortOptions = [
                'newest' => 'Newest first',
                'oldest' => 'Oldest first',
                'name_asc' => 'Name: A–Z',
                'name_desc' => 'Name: Z–A',
                'updated' => 'Recently updated',
            ];
            $sort = is_string($sort) && isset($sortOptions[$sort]) ? $sort : 'newest';

            $statusOptions = [
                'active' => 'Active',
                'stored' => 'Stored',
                'maintenance' => 'Maintenance',
                'broken' => 'Broken',
                'retired' => 'Retired',
                'sold' => 'Sold',
                'lost' => 'Lost',
            ];
            $conditionOptions = [
                'excellent' => 'Excellent',
                'good' => 'Good',
                'fair' => 'Fair',
                'poor' => 'Poor',
                'broken' => 'Broken',
            ];
            $statusColors = [
                'active' => '#6ed9a6',
                'stored' => '#b7afc6',
                'maintenance' => '#ffb36b',
                'broken' => '#ff879c',
                'retired' => '#b7afc6',
                'sold' => '#b7afc6',
                'lost' => '#ff879c',
            ];

            $filterGroups = [
                'asset_type_ids' => [
                    'label' => 'Asset types',
                    'options' => collect($assetTypes ?? [])->pluck('name', 'id')->all(),
                    'empty' => 'No asset types available.',
                ],
                'tag_ids' => [
                    'label' => 'Tags',
                    'options' => collect($tags ?? [])->pluck('name', 'id')->all(),
                    'empty' => 'No tags available.',
                ],
                'statuses' => [
                    'label' => 'Status',
                    'options' => $statusOptions,
                ],
                'conditions' => [
                    'label' => 'Condition',
                    'options' => $conditionOptions,
                ],
                'visibility' => [
                    'label' => 'Visibility',
                    'options' => ['private' => 'Private', 'public' => 'Public'],
                ],
            ];

            $selectedFilters = [];
            $filterCount = 0;

            foreach ($filterGroups as $field => $group) {
                $values = request()->query($field, []);
                $values = is_array($values) ? $values : [];
                $values = array_filter($values, fn ($value) => is_string($value) || is_int($value));
                $values = array_unique(array_map('strval', $values));
                $allowedValues = array_map('strval', array_keys($group['options']));
                $selectedFilters[$field] = array_values(array_intersect($values, $allowedValues));
                $filterCount += count($selectedFilters[$field]);
            }

            $filtersOpen = $filterCount > 0 || $sort !== 'newest';
            $hasSearchOrFilters = trim($query) !== '' || $filterCount > 0;
        @endphp

        @include('partials.navbar')

        <main class="container pb-5 am-assets">
            <h3 class="text-center my-3">My Assets</h3>
            <div class="row justify-content-center g-4">
                <div class="col-12 col-lg-7">
                    <div class="mx-auto" >
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
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

                        <form method="GET" action="{{ route('asset.index') }}" class="card mb-4" role="search" aria-label="Search and filter your assets">
                            <div class="card-body p-3">
                                <label for="asset-search" class="visually-hidden">Search your assets</label>
                                <div class="input-group">
                                    <input id="asset-search" type="search" name="q" value="{{ $query }}" class="form-control" placeholder="Search your assets..." maxlength="255">
                                    <button type="submit" class="btn btn-outline-primary" aria-label="Search assets" title="Search">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <circle cx="10.5" cy="10.5" r="6.5"/>
                                            <path d="m16 16 5 5"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1 {{ $filtersOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#assetFilters" aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}" aria-controls="assetFilters">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M4 7h16M4 17h16"/>
                                            <circle cx="9" cy="7" r="2" fill="var(--bs-body-bg)"/>
                                            <circle cx="15" cy="17" r="2" fill="var(--bs-body-bg)"/>
                                        </svg>
                                        <span>Filters</span>
                                        @if ($filterCount > 0)
                                            <span class="badge text-bg-secondary">{{ $filterCount }}</span>
                                        @endif
                                    </button>
                                </div>
                                <div class="text-end mt-2">
                                    <a href="{{ route('asset.index') }}" class="small link-secondary text-decoration-underline">Clear search &amp; filters</a>
                                </div>

                                <div class="collapse {{ $filtersOpen ? 'show' : '' }}" id="assetFilters">
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="h6 mb-0">Filter your assets</h4>
                                        <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#assetFilters" aria-label="Close filters" aria-controls="assetFilters"></button>
                                    </div>
                                    <p class="small text-body-secondary mb-3">Choose any matching option within each group. Different groups narrow the results together.</p>
                                    <div class="row g-3">
                                        @foreach ($filterGroups as $field => $group)
                                            <fieldset class="col-12 col-sm-6">
                                                <legend class="float-none w-auto fs-6 fw-semibold mb-2">{{ $group['label'] }}</legend>
                                                <div class="overflow-auto" style="max-height: 10rem;">
                                                    @forelse ($group['options'] as $value => $label)
                                                        <div class="form-check">
                                                            <input id="filter-{{ $field }}-{{ $loop->index }}" type="checkbox" name="{{ $field }}[]" value="{{ $value }}" class="form-check-input" @checked(in_array((string) $value, $selectedFilters[$field], true))>
                                                            <label for="filter-{{ $field }}-{{ $loop->index }}" class="form-check-label text-break">{{ $label }}</label>
                                                        </div>
                                                    @empty
                                                        <p class="small text-body-secondary mb-0">{{ $group['empty'] ?? 'No options available.' }}</p>
                                                    @endforelse
                                                </div>
                                            </fieldset>
                                        @endforeach
                                        <div class="col-12">
                                            <label for="asset-sort" class="form-label fw-semibold">Sort by</label>
                                            <select id="asset-sort" name="sort" class="form-select">
                                                @foreach ($sortOptions as $value => $label)
                                                    <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">Apply filters</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        @forelse ($assets as $asset)
                            <div class="card mb-3">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <h5 class="card-title mb-3 text-break">{{ $asset->name }}</h5>
                                        <span class="text-body-secondary flex-shrink-0" title="{{ $asset->is_public ? 'Public asset' : 'Private asset' }}">
                                            <span class="visually-hidden">{{ $asset->is_public ? 'Public asset' : 'Private asset' }}</span>
                                            @if ($asset->is_public)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <ellipse cx="12" cy="12" rx="4" ry="9"/>
                                                    <path d="M3 12h18"/>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                                    <rect x="5" y="10" width="14" height="11" rx="2"/>
                                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                                    <circle cx="12" cy="15" r="1"/>
                                                </svg>
                                            @endif
                                        </span>
                                    </div>
                                    @if (filled($asset->model) || filled($asset->manufacturer))
                                        <h6 class="card-subtitle mb-2 text-body-secondary text-break">{{ $asset->model }}{{ filled($asset->model) && filled($asset->manufacturer) ? ' | ' : '' }}{{ $asset->manufacturer }}</h6>
                                    @endif
                                    @if (filled($asset->assetType?->name))
                                        <h6 class="card-text mb-3 px-1 text-body-secondary">{{ $asset->assetType->name }}</h6>
                                    @endif
                                    @if (isset($statusColors[$asset->status]))
                                        <p>Status: <span style="color: {{ $statusColors[$asset->status] }}; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                    @endif
                                    @if (filled($asset->location))
                                        <p>Location: <span class="text-break" style="text-transform: uppercase; font-size: 75%">{{ $asset->location }}</span></p>
                                    @endif
                                    @if (!empty($asset->specifications))
                                        <div>
                                            <p class="mb-1">Specifications:</p>
                                            @foreach ($asset->specifications as $key => $value)
                                                @if (is_scalar($value) && (is_bool($value) || trim((string) $value) !== ''))
                                                    <p class="my-0 text-break" style="text-transform: uppercase; font-size: 75%">&gt; {{ str_replace('_', ' ', $key) }}: {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</p>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="text-start mt-4">
                                        <a href="{{ route('asset.show', ['id' => $asset->id]) }}" class="btn btn-primary">Details</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card border-0">
                                <div class="card-body py-4 text-center">
                                    @if ($hasSearchOrFilters)
                                        <h5>No matching assets</h5>
                                        <p class="text-body-secondary">Try another search or remove some filters.</p>
                                        <a href="{{ route('asset.index') }}" class="small link-secondary text-decoration-underline">Clear search &amp; filters</a>
                                    @else
                                        <h5>You haven't added any assets yet.</h5>
                                        <p class="text-body-secondary mb-0">Click the Add button in the Control Panel to get started.</p>
                                    @endif
                                </div>
                            </div>
                        @endforelse

                        @if ($assets instanceof \Illuminate\Contracts\Pagination\Paginator)
                            <div class="mt-4">
                                {{ $assets->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card am-control-panel">
                        <div class="card-body p-4">
                            <h3 class="card-title mb-3">Asset Control Panel</h3>
                            <h6 class="card-subtitle mb-3 text-body-secondary">You can manage your Assets here</h6>
                            <hr>
                            <p class="card-subtitle text-body-secondary">Add a new Asset to your profile</p>
                            <a href="{{ route('asset.create') }}" class="btn btn-outline-primary mb-3">Add a new Asset</a>
                            <p class="card-subtitle text-body-secondary">Sync an existing Asset for advanced features</p>
                            <button type="button" class="btn btn-outline-primary mb-3" disabled>Sync an existing Asset (WIP)</button>
                            <p class="card-subtitle text-body-secondary">Download your inventory</p>
                            <button type="button" class="btn btn-outline-primary mb-3" disabled>Export Assets (WIP)</button>
                            <p class="card-subtitle text-body-secondary">Create, rename, recolor, and delete your personal Tags</p>
                            <button type="button" class="btn btn-outline-primary mb-3" disabled>Manage Tags (WIP)</button>
                            <p class="card-subtitle text-body-secondary">Add a similar device using an existing asset</p>
                            <button type="button" class="btn btn-outline-primary mb-3" disabled>Duplicate an Asset (WIP)</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>