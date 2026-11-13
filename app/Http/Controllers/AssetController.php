<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\AssetType;
use App\Models\Asset;
use App\Models\User;

use App\Rules\NoProfanity;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->query(), [
            'q' => ['nullable', 'string', 'max:255'],
            'asset_type_ids' => ['sometimes', 'array', 'max:100'],
            'asset_type_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('asset_types', 'id'),
            ],
            'tag_ids' => ['sometimes', 'array', 'max:100'],
            'tag_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('tags', 'id')->where('user_id', $user->id),
            ],
            'statuses' => ['sometimes', 'array', 'max:7'],
            'statuses.*' => [
                'required', 'string', 'distinct',
                Rule::in(['active', 'stored', 'maintenance', 'broken', 'retired', 'sold', 'lost']),
            ],
            'conditions' => ['sometimes', 'array', 'max:5'],
            'conditions.*' => [
                'required', 'string', 'distinct',
                Rule::in(['excellent', 'good', 'fair', 'poor', 'broken']),
            ],
            'visibility' => ['sometimes', 'array', 'max:2'],
            'visibility.*' => [
                'required', 'string', 'distinct',
                Rule::in(['private', 'public']),
            ],
            'sort' => [
                'sometimes', 'required', 'string',
                Rule::in(['newest', 'oldest', 'name_asc', 'name_desc', 'updated']),
            ],
            'page' => ['sometimes', 'required', 'integer', 'min:1', 'max:1000000'],
        ], [
            'tag_ids.*.exists' => 'One of the selected tags is unavailable.',
            'asset_type_ids.*.exists' => 'One of the selected asset types is unavailable.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('asset.index')->withErrors($validator);
        }

        $filters = $validator->validated();

        $assetTypes = AssetType::orderBy('name')->get(['id', 'name']);
        $tags = $user->tags()->orderBy('name')->get(['id', 'name']);

        $query = $user->assets()->with('assetType');

        $search = trim($filters['q'] ?? '');

        if ($search !== '') {
            $pattern = '%' . str_replace(
                ['!', '%', '_'],
                ['!!', '!%', '!_'],
                $search
            ) . '%';

            $query->where(function ($searchQuery) use ($pattern, $user) {
                $columns = [
                    'assets.name',
                    'assets.manufacturer',
                    'assets.model',
                    'assets.model_number',
                    'assets.serial_number',
                    'assets.location',
                    'assets.description',
                ];

                foreach ($columns as $column) {
                    $searchQuery->orWhereRaw(
                        "{$column} LIKE ? ESCAPE '!'",
                        [$pattern]
                    );
                }

                $searchQuery->orWhereHas('assetType', function ($typeQuery) use ($pattern) {
                    $typeQuery->whereRaw("asset_types.name LIKE ? ESCAPE '!'", [$pattern]);
                });

                $searchQuery->orWhereHas('tags', function ($tagQuery) use ($pattern, $user) {
                    $tagQuery->where('tags.user_id', $user->id)
                        ->whereRaw("tags.name LIKE ? ESCAPE '!'", [$pattern]);
                });
            });
        }

        if (!empty($filters['asset_type_ids'])) {
            $query->whereIn('assets.asset_type_id', $filters['asset_type_ids']);
        }

        if (!empty($filters['tag_ids'])) {
            $query->whereHas('tags', function ($tagQuery) use ($filters, $user) {
                $tagQuery->where('tags.user_id', $user->id)
                    ->whereIn('tags.id', $filters['tag_ids']);
            });
        }

        if (!empty($filters['statuses'])) {
            $query->whereIn('assets.status', $filters['statuses']);
        }

        if (!empty($filters['conditions'])) {
            $query->whereIn('assets.condition', $filters['conditions']);
        }

        if (!empty($filters['visibility'])) {
            $visibilityValues = array_map(
                fn ($visibility) => $visibility === 'public' ? 1 : 0,
                $filters['visibility']
            );

            $query->whereIn('assets.is_public', $visibilityValues);
        }

        $sortOptions = [
            'newest' => ['assets.created_at', 'desc'],
            'oldest' => ['assets.created_at', 'asc'],
            'name_asc' => ['assets.name', 'asc'],
            'name_desc' => ['assets.name', 'desc'],
            'updated' => ['assets.updated_at', 'desc'],
        ];

        [$sortColumn, $sortDirection] = $sortOptions[$filters['sort'] ?? 'newest'];

        $assets = $query
            ->orderBy($sortColumn, $sortDirection)
            ->orderBy('assets.id', $sortDirection)
            ->paginate(12)
            ->withQueryString();

        if ($assets->currentPage() > $assets->lastPage()) {
            $filters['page'] = $assets->lastPage();

            return redirect()->route('asset.index', $filters);
        }

        return view('assets.index', compact('assets', 'assetTypes', 'tags'));
    }

    public function create(Request $request)
    {
        $assetTypes = AssetType::orderBy('name')->get();
        $tags = $request->user()->tags()->orderBy('name')->get();

        return view('assets.create', compact('assetTypes', 'tags'));
    }

    public function explore(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'q' => ['nullable', 'string', 'max:255'],
            'asset_type_ids' => ['sometimes', 'array', 'max:100'],
            'asset_type_ids.*' => [
                'required', 'integer', 'distinct',
                Rule::exists('asset_types', 'id'),
            ],
            'statuses' => ['sometimes', 'array', 'max:7'],
            'statuses.*' => [
                'required', 'string', 'distinct',
                Rule::in(['active', 'stored', 'maintenance', 'broken', 'retired', 'sold', 'lost']),
            ],
            'conditions' => ['sometimes', 'array', 'max:5'],
            'conditions.*' => [
                'required', 'string', 'distinct',
                Rule::in(['excellent', 'good', 'fair', 'poor', 'broken']),
            ],
            'sort' => [
                'sometimes', 'required', 'string',
                Rule::in(['most_viewed', 'newest', 'oldest', 'name_asc', 'name_desc', 'updated']),
            ],
            'page' => ['sometimes', 'required', 'integer', 'min:1', 'max:1000000'],
        ], [
            'asset_type_ids.*.exists' => 'One of the selected asset types is unavailable.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('asset.explore')->withErrors($validator);
        }

        $filters = $validator->validated();

        $assetTypes = AssetType::orderBy('name')->get(['id', 'name']);

        $query = Asset::where('assets.is_public', true)
            ->select([
                'assets.id',
                'assets.user_id',
                'assets.asset_type_id',
                'assets.name',
                'assets.manufacturer',
                'assets.model',
                'assets.status',
                'assets.condition',
                'assets.views_count',
            ])
            ->with(['assetType:id,name', 'user:id,username']);

        $search = trim($filters['q'] ?? '');

        if ($search !== '') {
            $pattern = '%' . str_replace(
                ['!', '%', '_'],
                ['!!', '!%', '!_'],
                $search
            ) . '%';

            $query->where(function ($searchQuery) use ($pattern) {
                $columns = [
                    'assets.name',
                    'assets.manufacturer',
                    'assets.model',
                    'assets.model_number',
                ];

                foreach ($columns as $column) {
                    $searchQuery->orWhereRaw(
                        "{$column} LIKE ? ESCAPE '!'",
                        [$pattern]
                    );
                }

                $searchQuery->orWhereHas('assetType', function ($typeQuery) use ($pattern) {
                    $typeQuery->whereRaw("asset_types.name LIKE ? ESCAPE '!'", [$pattern]);
                });

                $searchQuery->orWhereHas('user', function ($userQuery) use ($pattern) {
                    $userQuery->whereRaw("users.username LIKE ? ESCAPE '!'", [$pattern]);
                });
            });
        }

        if (!empty($filters['asset_type_ids'])) {
            $query->whereIn('assets.asset_type_id', $filters['asset_type_ids']);
        }

        if (!empty($filters['statuses'])) {
            $query->whereIn('assets.status', $filters['statuses']);
        }

        if (!empty($filters['conditions'])) {
            $query->whereIn('assets.condition', $filters['conditions']);
        }

        $sortOptions = [
            'most_viewed' => ['assets.views_count', 'desc'],
            'newest' => ['assets.created_at', 'desc'],
            'oldest' => ['assets.created_at', 'asc'],
            'name_asc' => ['assets.name', 'asc'],
            'name_desc' => ['assets.name', 'desc'],
            'updated' => ['assets.updated_at', 'desc'],
        ];

        [$sortColumn, $sortDirection] = $sortOptions[$filters['sort'] ?? 'most_viewed'];

        $assets = $query
            ->orderBy($sortColumn, $sortDirection)
            ->orderBy('assets.id', $sortDirection)
            ->paginate(12)
            ->withQueryString();

        if ($assets->currentPage() > $assets->lastPage()) {
            $filters['page'] = $assets->lastPage();

            return redirect()->route('asset.explore', $filters);
        }

        return view('assets.explore', compact('assets', 'assetTypes'));
    }

    public function show(string $id) 
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return redirect()->route('asset.index')->with('error', 'Invalid parameter. We couldn\'t find this Asset.');
        }
        
        try {
            $asset = auth()->user()->assets()->findOrFail($id);

            return view('assets.show', compact('asset'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('asset.index')->with('error', 'Asset not found or is private.');
        }
    }

    public function explore_show(string $id)
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return redirect()->route('asset.explore')->with('error', 'Invalid parameter. We couldn\'t find this Asset.');
        }

        try {
            $asset = Asset::where('is_public', true)
                ->select([
                    'id',
                    'user_id',
                    'asset_type_id',
                    'name',
                    'manufacturer',
                    'model',
                    'specifications',
                    'is_public',
                    'views_count',
                ])
                ->with([
                    'assetType:id,name',
                    'user:id,username',
                ])
                ->findOrFail($id);

            return view('assets.explore_show', compact('asset'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('asset.explore')->with('error', 'Asset not found or is private.');
        }
    }

    public function edit(Request $request, string $id)
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return redirect()->back()->with('error', 'Invalid parameter. We couldn\'t find this Asset.');
        }

        try {
            $asset = $request->user()->assets()->with('tags')->findOrFail($id);
            $assetTypes = AssetType::orderBy('name')->get();
            $tags = $request->user()->tags()->orderBy('name')->get();

            return view('assets.edit', compact('asset', 'assetTypes', 'tags'));
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Asset not found or unavailable.');
        }
    }

    public function store(Request $request)
    {
        [$data, $tagIds] = $this->validateAssetData($request);

        try {
            DB::transaction(function () use ($request, $data, $tagIds) {
                $asset = $request->user()->assets()->create($data);
                $asset->tags()->sync($tagIds);
            });

            return redirect()->route('asset.index')->with('success', 'Asset created successfully.');
        } catch (QueryException $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'The asset could not be saved. Please try again.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $asset = $request->user()->assets()->findOrFail($request->input('id'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('asset.index')->with('error', 'Asset not found or unavailable.');
        }

        [$data, $tagIds] = $this->validateAssetData($request);

        try {
            DB::transaction(function () use ($asset, $data, $tagIds) {
                $asset->update($data);
                $asset->tags()->sync($tagIds);
            });

            return redirect()->route('asset.index')->with('success', 'Asset updated successfully.');
        } catch (QueryException $e) {
            report($e);

            return redirect()->back()->withInput()->with('error', 'The changes could not be saved. Please try again.');
        }
    }

    private function validateAssetData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['bail', 'required', 'string', 'max:255', new NoProfanity],
            'manufacturer' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'model' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'model_number' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'seller' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'location' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'description' => ['bail', 'nullable', 'string', 'max:10000', new NoProfanity],
            'specification_rows.*.key' => ['bail', 'nullable', 'string', 'max:100', new NoProfanity],
            'specification_rows.*.value' => ['bail', 'nullable', 'string', 'max:255', new NoProfanity],
            'asset_type_id' => ['required', 'integer', Rule::exists('asset_types', 'id')],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'active', 'stored', 'maintenance', 'broken', 'retired', 'sold', 'lost',
            ])],
            'condition' => ['required', Rule::in([
                'excellent', 'good', 'fair', 'poor', 'broken',
            ])],
            'acquisition_condition' => ['nullable', Rule::in([
                'new', 'used', 'refurbished',
            ])],
            'acquisition_method' => ['nullable', Rule::in([
                'purchased', 'gifted', 'inherited', 'other',
            ])],

            'purchase_date' => ['nullable', 'date_format:Y-m-d'],
            'purchase_price' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99', 'decimal:0,2'],
            'currency' => ['nullable', 'required_with:purchase_price', Rule::in([
                'EUR', 'HUF', 'USD', 'GBP', 'CHF',
            ])],
            'warranty_expires_at' => ['nullable', 'date_format:Y-m-d'],
            'is_public' => ['required', 'boolean'],

            'tags' => ['sometimes', 'array', 'max:100'],
            'tags.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('tags', 'id')->where('user_id', $request->user()->id),
            ],

            'specification_rows' => ['sometimes', 'array', 'max:50'],
            'specification_rows.*' => ['array:key,value'],
            'specification_rows.*.key' => ['nullable', 'string', 'max:100'],
            'specification_rows.*.value' => ['nullable', 'string', 'max:255'],
        ], [
            'currency.required_with' => 'Please select a currency when entering a purchase price.',
            'tags.*.exists' => 'One of the selected tags is unavailable or does not belong to you.',
            'tags.*.distinct' => 'The same tag cannot be selected more than once.',
        ]);

        $specifications = [];
        $specificationErrors = [];

        foreach ($validated['specification_rows'] ?? [] as $index => $row) {
            $key = trim($row['key'] ?? '');
            $value = trim($row['value'] ?? '');

            if ($key === '' && $value === '') {
                continue;
            }

            if ($key === '') {
                $specificationErrors["specification_rows.$index.key"] = 'Each specification needs a name.';
                continue;
            }

            if ($value === '') {
                $specificationErrors["specification_rows.$index.value"] = "Please enter a value for \"$key\".";
                continue;
            }

            if (array_key_exists($key, $specifications)) {
                $specificationErrors["specification_rows.$index.key"] = "The specification \"$key\" appears more than once.";
                continue;
            }

            $specifications[$key] = $value;
        }

        if ($specificationErrors !== []) {
            throw ValidationException::withMessages($specificationErrors);
        }

        $tagIds = $validated['tags'] ?? [];

        unset($validated['tags'], $validated['specification_rows']);

        $validated['is_public'] = $request->boolean('is_public');
        $validated['specifications'] = $specifications === [] ? null : $specifications;

        return [$validated, $tagIds];
    }

    public function delete(Request $request, string $id) 
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return redirect()->back()->with('error', 'Invalid parameter. We couldn\'t find this Asset.');
        }

        try {
            $asset = $request->user()->assets()->findOrFail($id);
            $user_id = auth()->user()->id;
            $asset_user_id = $asset->user_id;

            if ($asset_user_id == $user_id) {
                return view('assets.destroy', compact('asset'));
            } else {
                return redirect()->back()->with('error', 'You don\'t have permission to this Asset.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Asset not found or you don\'t have permission to this Asset.');
        }
    }

    public function destroy(Request $request, string $id) 
    {
        if (!ctype_digit($id) || (int) $id < 1) {
            return redirect()->back()->with('error', 'Invalid parameter. We couldn\'t find this Asset.');
        }
        try {
            $asset = $request->user()->assets()->findOrFail($id);
            $user_id = auth()->user()->id;
            $asset_user_id = $asset->user_id;

            if ($asset_user_id == $user_id) {
                $asset->delete();

                return redirect()->route('asset.index')->with('success', 'Asset moved to Trash successfully.');
            } else {
                return redirect()->back()->with('error', 'You don\'t have permission to this Asset.');
            }
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'We encountered an error.');
        }

    }
}
