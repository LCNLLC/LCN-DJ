@php
    // Create a prefix string based on the category level
    $prefix = '';
    for ($i = 0; $i < $child_category->level; $i++){
        $prefix .= '-';
    }
@endphp
<li id="cat-{{ $child_category->id }}">
    <label>
        <input type="checkbox" name="category_ids[]" value="{{ $child_category->id }}">
        {{ $prefix }} {{ $child_category->getTranslation('name') }}
    </label>
    <label class="ml-2">
        <input type="radio" name="category_id" value="{{ $child_category->id }}">
        {{ translate('Main') }}
    </label>

    @if ($child_category->childrenCategories && count($child_category->childrenCategories))
        <ul>
            @foreach ($child_category->childrenCategories as $child)
                @include('backend.product.products.child_category', ['child_category' => $child])
            @endforeach
        </ul>
    @endif
</li>
