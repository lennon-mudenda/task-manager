<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\TaskFactory;

/**
 * @mixin IdeHelperTask
 */
#[Fillable(['uuid', 'user_id', 'project_id', 'name', 'priority'])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'user_id' => 'integer',
            'project_id' => 'integer',
            'name' => 'string',
            'priority' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get a task's priority ie when created before reorder.
     *
     * @return int
     */
    public static function getDefaultPriority(): int
    {
        if (self::query()->count() === 0) return 1;

        return self::query()->max('priority') + 1;
    }
}
