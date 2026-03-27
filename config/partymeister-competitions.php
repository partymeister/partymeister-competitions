<?php

return [
    // When true, release files will only be published if ALL qualified entries
    // in a competition have their final file confirmed (final_file_media_id set).
    'require_all_final_files_for_release' => (bool) env('PM_REQUIRE_ALL_FINAL_FILES_FOR_RELEASE', false),
    'shader_showdown_token' => env('SHADER_SHOWDOWN_TOKEN', ''),
];
