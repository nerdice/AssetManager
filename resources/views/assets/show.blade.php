<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Asset | AssetManager</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="d-flex flex-column min-vh-100">
        @include('partials.navbar')        

        <div class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-1"></div>
                    <div class="col py-3">
                        @if($asset)
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    @if (session('error'))
                                        <div class="alert alert-danger my-3" role="alert">{{ session('error') }}</div>
                                    @endif
                                    <h3 class="card-title mt-2 mb-3">{{ $asset->name }}</h5>
                                    <h4 class="card-subtitle mb-2 text-body-secondary">{{ $asset->model }} | {{ $asset->manufacturer }}</h6>
                                    <h5 class="card-text mb-3 px-1 text-body-secondary">{{ $asset->assetType->name }} </h6>
                                    @if ($asset->description)
                                        <p class="card-text">{{ $asset->description }}</p>
                                    @endif
                                    @if (isset($asset->serial_number))
                                        <p class="card-text">Serial Number: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->serial_number }}</span></p>
                                    @endif
                                    @if (isset($asset->model_number))
                                        <p class="card-text">Model Number: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->model_number }}</span></p>
                                    @endif
                                        
                                    <!-- STATUS -->
                                    @switch ($asset->status)
                                        @case ('active')
                                            <p>Status: <span style="color: #6ed9a6; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('stored')
                                            <p>Status: <span style="color: #b7afc6; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('maintenance')
                                            <p>Status: <span style="color: #ffb36b; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('broken')
                                            <p>Status: <span style="color: #ff879c; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('retired')
                                            <p>Status: <span style="color: #b7afc6; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('sold')
                                            <p>Status: <span style="color: #b7afc6; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @case ('active')
                                            <p>lost: <span style="color: #ff879c; text-transform: uppercase; font-size: 75%">{{ $asset->status }}</span></p>
                                            @break
                                        @default
                                            @break
                                    @endswitch

                                    <!-- CONDITION -->
                                    @switch ($asset->condition)
                                        @case ('excellent')
                                            <p>Condition: <span style="color: #b99aff; text-transform: uppercase; font-size: 75%">{{ $asset->condition }}</span></p>
                                            @break
                                        @case ('good')
                                            <p>Condition: <span style="color: #6ed9a6; text-transform: uppercase; font-size: 75%">{{ $asset->condition }}</span></p>
                                            @break
                                        @case ('fair')
                                            <p>Condition: <span style="color: #ffb36b; text-transform: uppercase; font-size: 75%">{{ $asset->condition }}</span></p>
                                            @break
                                        @case ('poor')
                                            <p>Condition: <span style="color: #ff879c; text-transform: uppercase; font-size: 75%">{{ $asset->condition }}</span></p>
                                            @break
                                        @case ('broken')
                                            <p>Condition: <span style="color: #ff879c; text-transform: uppercase; font-size: 75%">{{ $asset->condition }}</span></p>
                                            @break
                                        @default
                                            @break
                                    @endswitch

                                    <!-- ACQUISITION CONDITION -->
                                    @switch ($asset->acquisition_condition)
                                        @case ('new')
                                            <p>Acquisition condition: <span style="color: #6ed9a6; text-transform: uppercase; font-size: 75%">{{ $asset->acquisition_condition }}</span></p>
                                            @break
                                        @case ('used')
                                            <p>Acquisition condition: <span style="color: #ffb36b; text-transform: uppercase; font-size: 75%">{{ $asset->acquisition_condition }}</span></p>
                                            @break
                                        @case ('refurbished')
                                            <p>Acquisition condition: <span style="color: #b99aff; text-transform: uppercase; font-size: 75%">{{ $asset->acquisition_condition }}</span></p>
                                            @break
                                        @default
                                            @break
                                    @endswitch
                                    
                                    @if ($asset->acquisition_method)
                                        <p>Acquisition method: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->acquisition_method }}</span></p>
                                    @endif
                                    @if ($asset->purchase_date)
                                        <p>Purchased on: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->purchase_date }}</span></p>
                                    @endif
                                    @if ($asset->purchase_price && $asset->currency)
                                        <p>Purchased for: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->purchase_price }} {{ $asset->currency }}</span></p>
                                    @endif
                                    @if ($asset->seller)
                                        <p>Seller: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->seller }}</span></p>
                                    @endif
                                    @if ($asset->warranty_expires_at)
                                        <p>Warranty expires at: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->warranty_expires_at }}</span></p>
                                    @endif
                                    @if ($asset->location)
                                        <p>Location: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->location }}</span></p>
                                    @endif


                                    @if ($asset->specifications)
                                        <p>Specifications: 
                                            @foreach ($asset->specifications as $key => $value)
                                                <p style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">> {{ str_replace('_', ' ', $key) }}: {{ $value }}</p>
                                            @endforeach                     
                                        </p>   
                                    @endif


                                    @if ($asset->created_at)
                                        <p>Created at: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->created_at }}</span></p>
                                    @endif
                                    @if ($asset->updated_at)
                                        <p>Updated at: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->updated_at }}</span></p>
                                    @endif
                                    @if ($asset->seller)
                                        <p>Seller: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->seller }}</span></p>
                                    @endif
                                        
                                    <a href="{{ route('asset.edit', ['id' => $asset->id]) }}" class="btn btn-primary">Edit Asset</a>
                                    <a href="{{ route('asset.delete', ['id' => $asset->id]) }}" class="btn btn-danger">Delete Asset</a>
                                    @if ($asset->is_public)
                                        <a href="{{ route('asset.explore_show', ['id' => $asset->id]) }}" class="btn btn-outline-primary">See in Explore</a>
                                    @endif
                                </div>
                            </div>

                        @endif
                    </div>
                    <div class="col-12 col-lg-1"></div>
                </div>
            </div>
        </div>
        
        @include('partials.footer')
    </body>
</html>
