<?php

use Illuminate\Support\Facades\DB;

test('the package\'s migrations run without errors', function() {
    $prices_columns = DB::select('PRAGMA table_info(prices);');

    $this->assertCount(9, $prices_columns);
});
