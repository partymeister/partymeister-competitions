<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Partymeister\Core\Models\Visitor;

/**
 * Partymeister\Competitions\Models\Comment
 *
 * @property int $id
 * @property int|null $visitor_id
 * @property int $read_by_visitor
 * @property int $read_by_organizer
 * @property string $model_type
 * @property int $model_id
 * @property string $message
 * @property string $author
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $model
 * @property-read Visitor|null $visitor
 *
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereAuthor($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereMessage($value)
 * @method static Builder|Comment whereModelId($value)
 * @method static Builder|Comment whereModelType($value)
 * @method static Builder|Comment whereReadByOrganizer($value)
 * @method static Builder|Comment whereReadByVisitor($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereVisitorId($value)
 * @mixin Eloquent
 */
class Comment extends Model
{
    /**
     * Get all of the owning commentable models.
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
