<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Movies
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $director
 * @property string|null $describe
 * @property int|null $rate
 * @property string $released
 * @property string|null $release_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereDescribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereReleaseAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereReleased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Movies whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Movies extends Model
{
    protected $table = 'movies';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
