<?php

namespace App\Rules;

use App\Models\MasterData\Subdistrict;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class SubdistrictExists implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    public function __construct(protected ?string $cityField = null) {}

    public static function on(string $cityField): static
    {
        return new static($cityField);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cityId = $this->cityField ? optional($this->data)[$this->cityField] : null;

        $exists = Subdistrict::where('id', $value)->when($cityId, fn (Builder $query) => $query->where('city_id', $cityId))->exists();

        if (!$exists) {
            $fail($cityId ? 'validation.subdistrict_exists' : 'validation.subdistrict_exists_on_city')->translate();
        }
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
