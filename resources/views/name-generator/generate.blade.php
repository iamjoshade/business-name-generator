@extends('layout.custom-layout')

@section('content')


<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <h1 class="display-5 fw-bold text-center mb-3">Free Business Name Generator UK</h1>
                <p class="text-center mb-4">
                    Generate business name ideas, for your new business, startup, or side hustle.
                    Get inspired with our free business name generator. Ready in under a minute.
                </p>

                <form class="card border-secondary rounded-4 bg-black bg-opacity-25 shadow" method="POST"
                    action="{{ route('generator.generate') }}">
                    @csrf
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <label for="keywords" class="form-label small text-uppercase text-secondary fw-semibold">
                                Keywords or business idea
                            </label>
                            <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                                class="form-control form-control-lg @error('keywords') is-invalid @enderror"
                                placeholder="e.g. fashion, swift logistics, food and drinks">
                            @error('keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="industry" class="form-label small text-uppercase text-secondary fw-semibold">
                                Industry
                            </label>
                            <select id="industry" name="industry"
                                class="form-select form-select-lg @error('industry') is-invalid @enderror">
                                <option value="" @selected(! old('industry'))>Choose an industry</option>
                                @foreach ($industries as $industry)
                                    <option value="{{ $industry }}" @selected(old('industry') === $industry)>
                                        {{ ucfirst($industry) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('industry')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary text-white btn-lg fw-semibold px-4">
                                Generate business names
                            </button>
                        </div>

                    </div>
                </form>

                @isset($names)
                    <section class="mt-5" id="results">

                        <h2 class="h4 fw-bold mb-3">Your business names</h2>
                        <div class="row g-2 mb-5">
                            @foreach ($names as $name)
                                <div class="col-sm-6">
                                    <div class="card border-secondary bg-black bg-opacity-25 h-100">
                                        <div class="card-body py-2 px-3">{{ $name }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h2 class="h4 fw-bold mb-3">Tagline ideas</h2>
                        <ul class="list-group mb-5">
                            @foreach ($taglines as $tagline)
                                <li class="list-group-item bg-black bg-opacity-25 border-secondary">{{ $tagline }}</li>
                            @endforeach
                        </ul>

                        <h2 class="h4 fw-bold mb-3">Brand palette</h2>
                        <div class="row g-2 mb-3">
                            @foreach (['primary', 'secondary', 'accent', 'light', 'background'] as $role)
                                <div class="col">
                                    <div class="rounded-3 border border-secondary mb-2"
                                        style="height: 64px; background-color: {{ $palette[$role] }};"></div>
                                    <div class="small text-capitalize">{{ $role }}</div>
                                    <div class="small text-secondary">{{ $palette[$role] }}</div>
                                </div>
                            @endforeach
                        </div>
                        <p class="small mb-0">
                            <span class="text-secondary">Heading font:</span> {{ $palette['font_heading'] }}
                            <span class="text-secondary ms-3">Body font:</span> {{ $palette['font_body'] }}
                        </p>

                    </section>
                @endisset

            </div>
        </div>
    </div>

@endsection
