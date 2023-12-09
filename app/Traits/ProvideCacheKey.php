<?php

namespace App\Traits;




trait ProvideCacheKey
{


    public function cacheKey()
    {
        return sprintf(
            "%s/%s",
            $this->getTable(),
            $this->getKey(),
            // $this->updated_at->timestamp
        );
    }
}
