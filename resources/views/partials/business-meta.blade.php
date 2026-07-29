@php
    if (!isset($slug)) {
        $slug = request('slug', request('business', 'dariv'));
    }
    $currentSlug = $slug;
    if ($slug === 'dariv') {
        $unitName = 'DARIV Waterproofing';
        $unitType = 'Residential & Roof Sealing';
        $unitIcon = '☔';
        $unitBadgeBg = 'bg-[#E4EEF3] text-[#2A5F7A]';
        $sidebarExtras = ['Roof Estimator', 'Warranty Ledger', 'Client Inquiries'];
    } elseif ($slug === 'hydroguard') {
        $unitName = 'HydroGuard Solutions';
        $unitType = 'Commercial & Foundations';
        $unitIcon = '🛡️';
        $unitBadgeBg = 'bg-[#E6F4EA] text-[#137333]';
        $sidebarExtras = ['Basement Grouting', 'Industrial Quotes', 'Client Inquiries'];
    } else {
        $slug = 'drymax';
        $currentSlug = 'drymax';
        $unitName = 'DryMax Sealants';
        $unitType = 'Interior & Bathrooms';
        $unitIcon = '🚿';
        $unitBadgeBg = 'bg-[#F4EBE0] text-[#7A4A2B]';
        $sidebarExtras = ['Bathroom Sealing', 'Tile Regrouting', 'Client Inquiries'];
    }
@endphp
