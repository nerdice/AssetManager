<!DOCTYPE html>
<html data-bs-theme="dark" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Delete Asset | AssetManager</title>
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
                                    @if (session('success'))
                                        <div class="alert alert-success my-3" role="alert">{{ session('success') }}</div>
                                    @endif
                                    <h3 class="card-title mt-2 mb-3">{{ $asset->name }}</h5>
                                    <h4 class="card-subtitle mb-2 text-body-secondary">{{ $asset->model }} | {{ $asset->manufacturer }}</h6>
                                    <h5 class="card-text mb-3 px-1 text-body-secondary">{{ $asset->assetType->name }} </h6>
                                    @if ($asset->description)
                                        <p class="card-text">{{ $asset->description }}</p>
                                    @endif
                                    
                                    @if ($asset->created_at)
                                        <p>Created at: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->created_at }}</span></p>
                                    @endif
                                    @if ($asset->updated_at)
                                        <p>Last updated at: <span style="color: var(--bs-body-color); text-transform: uppercase; font-size: 75%">{{ $asset->updated_at }}</span></p>
                                    @endif
                                        
                                </div>
                                <div style="text-align: center" class="mt-3">
                                    <h2>Are you sure you want to delete this Asset?</h2>
                                    <div class="row my-5">
                                        <div class="col-6" style="text-align: end">
                                            <form action="">
                                                <a href="{{ route('asset.show', ['id' => $asset->id]) }}" class="btn btn-success">Don't delete, go back</a>
                                            </form>
                                        </div>
                                        <div class="col-6" style="text-align: start">
                                            <form action="{{ route('asset.destroy', ['id' => $asset->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="submit" class="btn btn-danger" value="Yes, delete this Asset">
                                            </form>
                                        </div>
                                    </div>
                                    
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
