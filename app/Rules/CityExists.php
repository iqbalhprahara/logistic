<?php

namespace App\Rules;

use App\Models\MasterData\City;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class CityExists implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    public function __construct(protected ?string $provinceField = null) {}

    public static function on(string $provinceField): static
    {
        return new static($provinceField);
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $provinceId = $this->provinceField ? optional($this->data)[$this->provinceField] : null;

        $exists = City::where('id', $value)->when($provinceId, fn (Builder $query) => $query->where('province_id', $provinceId))->exists();

        if (!$exists) {
            $fail($provinceId ? 'validation.city_exists' : 'validation.city_exists_on_province')->translate();
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
