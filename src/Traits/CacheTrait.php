<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 11/07/2018
 * Time: 15:39
 */

namespace Z1lab\JsonApi\Traits;


trait CacheTrait
{
    /**
     * @param int $time
     *
     * @return int
     */
    public function cacheTime(int $time)
    {
        return (config('cache.lifetime') > 0) ? $time : 0;
    }

    /**
     * @return int
     */
    public function cacheDefault(): int
    {
        return config('cache.lifetime');
    }

    /**
     * @return int
     */
    public function cacheHour(): int
    {
        return (config('cache.lifetime') > 0) ? 60 : 0;
    }

    /**
     * @return int
     */
    public function cacheDay(): int
    {
        return (config('cache.lifetime') > 0) ? 1440 : 0;
    }

    /**
     * @return int
     */
    public function cacheMonth(): int
    {
        return (config('cache.lifetime') > 0) ? 43200 : 0;
    }
}
