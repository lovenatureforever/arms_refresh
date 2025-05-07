<?php

function states()
{
    return [
        "Johor",
        "Kedah",
        "Kelantan",
        "Melaka",
        "Negeri Sembilan",
        "Pahang",
        "Perak",
        "Perlis",
        "Pulau Pinang",
        "Sarawak",
        "Selangor",
        "Terengganu",
        "Kuala Lumpur",
        "Labuan",
        "Sabah",
        "Putrajaya"
    ];
}

function format_number($number)
{
    if (isset($number) && $number != 0) {
        $result = number_format(abs($number));
        $result = $number < 0 ? "($result)" : $result;
    } else {
        $result = '-';
    }

    return $result;
}

function readNumber($value)
{
    // Remove commas
    $value = str_replace(',', '', $value);

    // Check if the input has parentheses (accounting format for negative)
    if (preg_match('/^\((.*)\)$/', $value, $matches)) {
        return -1 * abs((float) $matches[1]);
    }

    return (float) $value;
}

function displayNumber($value)
{
    if ($value === '' || $value === null) {
        return '';
    }

    $number = floatval($value);
    $formatted = number_format(abs($number), 2); // Format with 2 decimal places
    $formatted = preg_replace('/\.?0+$/', '', $formatted);
    // Format negative numbers with parentheses
    return $number < 0 ? "($formatted)" : $formatted;
}

function removeTrailingZeros($number) {
    // Convert to float and back to string to remove trailing zeros
    return floatval($number);
}

function mbrsMappingList() {
    return [
        'Amortisation',
        'Biological assets',
        'Borrowing costs',
        'Borrowings',
        'Business combinations',
        'Cash and cash equivalents',
        'Collateral',
        'Construction contracts',
        'Contingent assets',
        'Contingent liabilities',
        'Decommissioning, restoration and rehabilitation provisions',
        'Deferred income tax',
        'Depreciation expense',
        'Dividend income',
        'Earnings per share',
        'Employee benefits',
        'Equity instruments',
        'Fee and commission income and expense',
        'Financial instruments',
        'Foreign currency',
        'Functional and presentation currency',
        'Goods sold',
        'Goodwill',
        'Government grants',
        'Impairment of non-financial assets',
        'Income tax',
        'Intangible assets',
        'Interest income and expense',
        'Inventories',
        'Investment in associates',
        'Investments in subsidiaries',
        'Investment property',
        'Investments in joint ventures',
        'Issue expenses',
        'Leases',
        'Non-current assets or disposal groups classified as held for sale and discontinued operations',
        'Onerous contracts',
        'Preference share capital',
        'Property, plant and equipment',
        'Provisions',
        'Rental income',
        'Restructuring',
        'Revenue and other income',
        'Segment reporting',
        'State plans',
        'Transactions with related parties',
        'Warranties',
        'Other significant accounting policies relevant to understanding of financial statements',
        'Basis of consolidation',
        'Basis of preparation of financial statements',
    ];
}
