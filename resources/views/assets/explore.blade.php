<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Explore | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        @php
            $query = request()->query('q', '');
            $query = is_string($query) ? $query : '';

            $sortOptions = [
                'most_viewed' => 'Most viewed',
                'newest' => 'Newest first',
                'oldest' => 'Oldest first',
                'name_asc' => 'Name: A–Z',
                'name_desc' => 'Name: Z–A',
                'updated' => 'Recently updated',
            ];
            $sort = request()->query('sort', 'most_viewed');
            $sort = is_string($sort) && isset($sortOptions[$sort]) ? $sort : 'most_viewed';

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

            $filterGroups = [
                'asset_type_ids' => [
                    'label' => 'Asset types',
                    'options' => collect($assetTypes ?? [])->pluck('name', 'id')->all(),
                ],
                'statuses' => [
                    'label' => 'Status',
                    'options' => $statusOptions,
                ],
                'conditions' => [
                    'label' => 'Condition',
                    'options' => $conditionOptions,
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

            $filtersOpen = $filterCount > 0 || $sort !== 'most_viewed';
            $hasSearchOrFilters = trim($query) !== '' || $filterCount > 0;
        @endphp

        @include('partials.navbar')

        <main class="container py-3">
            <h3 class="text-center mb-4">Explore</h3>
            <div class="mx-auto" style="max-width: 46rem;">
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

                <form method="GET" action="{{ route('asset.explore') }}" class="card mb-4" role="search" aria-label="Search and filter public assets">
                    <div class="card-body p-3">
                        <label for="explore-search" class="visually-hidden">Search public assets or usernames</label>
                        <div class="input-group">
                            <input id="explore-search" type="search" name="q" value="{{ $query }}" class="form-control" placeholder="Search assets or users..." maxlength="255">
                            <button type="submit" class="btn btn-outline-primary" aria-label="Search public assets" title="Search">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="10.5" cy="10.5" r="6.5"/>
                                    <path d="m16 16 5 5"/>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1 {{ $filtersOpen ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#exploreFilters" aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}" aria-controls="exploreFilters">
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
                            <a href="{{ route('asset.explore') }}" class="small link-secondary text-decoration-underline">Clear search &amp; filters</a>
                        </div>

                        <div class="collapse {{ $filtersOpen ? 'show' : '' }}" id="exploreFilters">
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="h6 mb-0">Filter public assets</h4>
                                <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#exploreFilters" aria-label="Close filters" aria-controls="exploreFilters"></button>
                            </div>
                            <p class="small text-body-secondary mb-3">Filters apply automatically. Choose any matching option within each group.</p>
                            <div class="row g-3">
                                @foreach ($filterGroups as $field => $group)
                                    <fieldset class="col-12 col-sm-6">
                                        <legend class="float-none w-auto fs-6 fw-semibold mb-2">{{ $group['label'] }}</legend>
                                        <div class="overflow-auto" style="max-height: 10rem;">
                                            @forelse ($group['options'] as $value => $label)
                                                <div class="form-check">
                                                    <input id="explore-{{ $field }}-{{ $loop->index }}" type="checkbox" name="{{ $field }}[]" value="{{ $value }}" class="form-check-input" onchange="this.form.requestSubmit()" @checked(in_array((string) $value, $selectedFilters[$field], true))>
                                                    <label for="explore-{{ $field }}-{{ $loop->index }}" class="form-check-label text-break">{{ $label }}</label>
                                                </div>
                                            @empty
                                                <p class="small text-body-secondary mb-0">No options available.</p>
                                            @endforelse
                                        </div>
                                    </fieldset>
                                @endforeach
                                <div class="col-12">
                                    <label for="explore-sort" class="form-label fw-semibold">Sort by</label>
                                    <select id="explore-sort" name="sort" class="form-select" onchange="this.form.requestSubmit()">
                                        @foreach ($sortOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                @if ($assets->total() > 0)
                    <p class="small text-body-secondary mb-3">Showing {{ $assets->firstItem() }}–{{ $assets->lastItem() }} of {{ $assets->total() }} public assets</p>
                @endif

                @forelse ($assets as $asset)
                    <div class="card mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="32" width="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-body-secondary flex-shrink-0" aria-hidden="true">
                                    <circle cx="12" cy="7" r="4"/>
                                    <path d="M4 22v-2a8 8 0 0 1 16 0v2"/>
                                </svg>
                                <p class="card-title mb-0 text-break">{{ $asset->user?->username ?? 'User' }}</p>
                            </div>

                            <h5 class="card-title mb-3 text-break">{{ $asset->name }}</h5>
                            @if (filled($asset->model) || filled($asset->manufacturer))
                                <h6 class="card-subtitle mb-2 text-body-secondary text-break">{{ $asset->model }}{{ filled($asset->model) && filled($asset->manufacturer) ? ' | ' : '' }}{{ $asset->manufacturer }}</h6>
                            @endif
                            @if (filled($asset->assetType?->name))
                                <h6 class="card-text mb-3 px-1 text-body-secondary">{{ $asset->assetType->name }}</h6>
                            @endif
                            <div class="text-start mt-4">
                                <a href="{{ route('asset.explore_show', ['id' => $asset->id]) }}" class="btn btn-primary">Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-4 text-center">
                        @if ($hasSearchOrFilters)
                            <h4>No matching assets</h4>
                            <p class="text-body-secondary">Try another search or remove some filters.</p>
                            <a href="{{ route('asset.explore') }}" class="small link-secondary text-decoration-underline">Clear search &amp; filters</a>
                        @else
                            <h4 class="fst-italic">It seems kind of empty around here...</h4>
                            <p class="text-body-secondary mt-3">Be the first one to share your devices💻</p>
                            @auth
                                <a class="btn btn-primary" href="{{ route('asset.index') }}">My Assets</a>
                            @else
                                <a class="btn btn-primary" href="{{ route('register') }}">Create an account</a>
                            @endauth
                        @endif
                    </div>
                @endforelse

                <div class="mt-4 mb-3">
                    {{ $assets->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </main>

        @include('partials.footer')
    </body>
</html>