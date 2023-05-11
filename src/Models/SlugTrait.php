<?php


namespace Yager\Laravelhelper\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yager\Phphelper\Stringhw;

trait SlugTrait
{

    protected static function bootSlugTrait()
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });
    }

    public function generateSlug()
    {
        if (!property_exists($this, 'slugField')) {
            return;
        }

        $unique = false;
        $tested = [];

        do {
            $string = Stringhw::random();

            if (in_array($string, $tested)) {
                continue;
            }

            $tested[] = $string;

            $count = DB::table($this->table)->where($this->slugField, '=', $string)->count();
            if ($count) {
                continue;
            }

            $unique = true;
        } while (!$unique);

        $this->{$this->slugField} = $string;
    }
}
