<?php

use App\Services\TextChunker;

test('sanitizes corrupted PDF markers and markup before chunking', function () {
    $text = "D<>A<>R<>I<>V<>\u{00A0}<g>Policy</g>\0\n  coverage   details ";

    expect(TextChunker::cleanExtractedText($text))
        ->toBe('DARIV Policy coverage details');
});

test('preserves ordinary punctuation while normalizing whitespace', function () {
    $text = "Keep commas, periods, and 100% coverage.\r\nNext line.";

    expect(TextChunker::cleanExtractedText($text))
        ->toBe('Keep commas, periods, and 100% coverage. Next line.');
});