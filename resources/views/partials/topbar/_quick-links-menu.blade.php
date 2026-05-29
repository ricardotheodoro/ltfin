@php
    $quickLinks = theme()->getOption('menu', 'quick_links', []);
    $quickLinksCount = count($quickLinks);
    $lastRow = $quickLinksCount > 0 ? intdiv($quickLinksCount - 1, 2) : 0;
@endphp

<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column w-250px w-lg-325px" data-kt-menu="true">
    <!--begin::Heading-->
    <div class="d-flex flex-column flex-center rounded-top px-9 py-10 bg-primary">
        <h3 class="text-white fw-bold mb-3">
            {{ __('Links rápidos') }}
        </h3>
        <span class="badge bg-white bg-opacity-25 text-primary py-2 px-3">{{ config('app.name') }}</span>
    </div>
    <!--end::Heading-->

    @if ($quickLinksCount > 0)
        <!--begin:Nav-->
        <div class="row g-0">
            @foreach ($quickLinks as $index => $link)
                @php
                    $column = $index % 2;
                    $row = intdiv($index, 2);
                    $itemClasses = 'd-flex flex-column flex-center h-100 p-6 bg-hover-light';
                    if ($column === 0) {
                        $itemClasses .= ' border-end';
                    }
                    if ($row < $lastRow) {
                        $itemClasses .= ' border-bottom';
                    }
                @endphp
                <div class="col-6">
                    <a href="{{ theme()->getPageUrl($link['path']) }}" class="{{ $itemClasses }}">
                        {!! $link['icon'] ?? '' !!}
                        <span class="fs-5 fw-bold text-gray-800 mb-0">{{ __($link['title']) }}</span>
                        @if (!empty($link['subtitle']))
                            <span class="fs-7 text-gray-400">{{ __($link['subtitle']) }}</span>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
        <!--end:Nav-->
    @endif
</div>
<!--end::Menu-->
