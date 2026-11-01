<?php

return [
    'segment_size' => (int) env('INGESTION_SEGMENT_SIZE', 100000),
    'max_extracted_text_length' => (int) env('INGESTION_MAX_EXTRACTED_TEXT_LENGTH', 5000000),
];
