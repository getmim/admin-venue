<?php
/**
 * Filter
 * @package admin-venue
 * @version 0.0.1
 */

namespace AdminVenue\Library;

use Venue\Model\Venue;

class Filter implements \Admin\Iface\ObjectFilter
{
    static function filter(array $cond): ?array{
        $cnd = [];
        if(isset($cond['q']) && $cond['q'])
            $cnd['q'] = (string)$cond['q'];
        $venues = Venue::get($cnd, 15, 1, ['title'=>true]);
        if(!$venues)
            return [];

        $result = [];
        foreach($venues as $venue){
            $result[] = [
                'id'    => (int)$venue->id,
                'label' => $venue->title,
                'info'  => $venue->title,
                'icon'  => NULL
            ];
        }

        return $result;
    }

    static function lastError(): ?string{
        return null;
    }
}