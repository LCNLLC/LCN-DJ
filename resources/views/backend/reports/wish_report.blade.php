@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
       <h1 class="h3">{{ translate('Product Wish Report') }}</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('wish_report.index') }}" method="GET">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="col-form-label">{{ translate('Sort by Category') }}:</label>
                            <select class="form-control aiz-selectpicker" name="category_id">
                                <option value="">{{ translate('Choose Category') }}</option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" @if($category->id == $sort_by) selected @endif>
                                        {{ $category->getTranslation('name') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label">{{ translate('Stock Filter') }}:</label>
                            <select class="form-control aiz-selectpicker" name="stock_filter">
                                <option value="">{{ translate('All Stock') }}</option>
                                <option value="low" @if(request('stock_filter') == 'low') selected @endif>{{ translate('Low Stock') }}</option>
                                <option value="out" @if(request('stock_filter') == 'out') selected @endif>{{ translate('Out of Stock') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="col-form-label"> </label>
                            <button class="btn btn-primary btn-block" type="submit">{{ translate('Filter') }}</button>
                        </div>
                    </div>
                </form>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ translate('Wishlist Summary by Category') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered aiz-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>{{ translate('Category') }}</th>
                                                <th>{{ translate('Total Wishes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($wishlistSummary as $summary)
                                                <tr>
                                                    <td>{{ \App\Models\Category::find($summary->category_id)->getTranslation('name') }}</td>
                                                    <td>{{ $summary->total_wishes }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>{{ translate('Product Name') }}</th>
                                <th>{{ translate('Category') }}</th>
                                <th>{{ translate('Current Stock') }}</th>
                                <th>{{ translate('Number of Wishes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->getTranslation('name') }}</td>
                                    <td>{{ $product->category ? $product->category->getTranslation('name') : 'N/A' }}</td>
                                    <td>
                                        @if($product->current_stock <= 0)
                                            <span class="badge badge-inline badge-danger">{{ translate('Out of Stock') }}</span>
                                        @elseif($product->current_stock <= config('settings.low_stock_threshold', 10))
                                            <span class="badge badge-inline badge-warning">{{ $product->current_stock }}</span>
                                        @else
                                            <span class="badge badge-inline badge-success">{{ $product->current_stock }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->wishlists ? $product->wishlists->count() : 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="aiz-pagination mt-4">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
