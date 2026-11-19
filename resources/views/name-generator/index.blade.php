@extends('layout.custom-layout')

@section('content')
    <div class="container">
        <div class="row">
            <section class="bg-dark text-white py-5">
                <div class="container py-4">

                    <div class="row justify-content-center text-center mb-4">
                        <div class="col-lg-8">
                            <h1 class="display-5 fw-bold mb-3">
                                Free Business Name Generator UK
                            </h1>
                            <p class="text-white mx-auto mb-0" style="max-width: 32rem;">
                                Generate business name ideas, for your new business, startup, or side hustle. Get inspired
                                with our free business name generator.
                                Ready in under a
                                minute.
                            </p>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-7 col-xl-6">
                            <form class="card border-secondary rounded-4 bg-black bg-opacity-25 shadow" method="POST"
                                action="#">
                                <div class="card-body p-4">

                                    <div class="mb-4">
                                        <label for="keywords"
                                            class="form-label small text-uppercase text-secondary fw-semibold">
                                            Keywords or business idea
                                        </label>
                                        <input type="text" id="keywords" name="keywords"
                                            class="form-control form-control-lg"
                                            placeholder="e.g. fashion, swift logistics, food and drinks">
                                    </div>

                                    <div class="mb-4">
                                        <label for="industry"
                                            class="form-label small text-uppercase text-secondary fw-semibold">
                                            Industry
                                        </label>
                                        <select id="industry" name="industry" class="form-select form-select-lg">
                                            <option selected>Choose an industry</option>
                                            <option value="fashion">Fashion</option>
                                            <option value="food-and-drink">Food and drink</option>
                                            <option value="logistics">Logistics</option>
                                            <option value="technology">Technology</option>
                                        </select>
                                    </div>



                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary text-white btn-lg fw-semibold px-4">
                                            Generate business names
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>
@endsection
