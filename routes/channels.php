<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('antrian-channel', function () {
    return true;
});
