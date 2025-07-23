@props(['active', 'id'])

@php
$tabs = [
    ['route' => 'corporate.yearend', 'label' => 'Year End Info'],
    ['route' => 'corporate.companyname', 'label' => 'Company Name'],
    ['route' => 'corporate.businessnature', 'label' => 'Nature of Business'],
    ['route' => 'corporate.companyaddress', 'label' => 'Registered Address'],
    ['route' => 'corporate.businessaddress', 'label' => 'Business Address'],
    ['route' => 'corporate.sharecapital', 'label' => 'Share Capital'],
    ['route' => 'corporate.directors', 'label' => 'Directors'],
    ['route' => 'corporate.shareholders', 'label' => 'Shareholders'],
    ['route' => 'corporate.secretaries', 'label' => 'Secretaries'],
    ['route' => 'corporate.auditor', 'label' => 'Auditor'],
    ['route' => 'corporate.charges', 'label' => 'Charges'],
    ['route' => 'corporate.dividends', 'label' => 'Dividends'],
    ['route' => 'corporate.reportinfo', 'label' => 'Report Info'],
];
@endphp

<nav class="flex-wrap rounded md:flex-col" role="tablist" aria-label="Tabs">
    @foreach ($tabs as $tab)
        <a class="btn border border-gray-100 mr-2 mb-2 {{ $active === $tab['route'] ? 'bg-primary active text-white' : 'bg-transparent' }}"
           role="tab"
           href="{{ route($tab['route'], ['id' => $id]) }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</nav>
