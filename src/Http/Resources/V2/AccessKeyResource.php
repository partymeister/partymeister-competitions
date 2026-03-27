<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\AccessKey;

/**
 * @mixin AccessKey
 */
class AccessKeyResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id'            => (int) $this->id,
            'access_key'    => $this->access_key,
            'ip_address'    => $this->ip_address,
            'registered_at' => $this->registered_at,
            'is_remote'     => (bool) $this->is_remote,
            'is_satellite'  => (bool) $this->is_satellite,
            'is_prepaid'    => (bool) $this->is_prepaid,
            'visitor_id'    => $this->visitor_id,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
