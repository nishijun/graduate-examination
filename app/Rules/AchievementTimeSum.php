<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AchievementTimeSum implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($achievement_time)
    {
        $this->achievement_time = $achievement_time;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $sum = 0;
      foreach ($value as $item) {
        $sum += $item;
      }
      return $sum <= (int)$this->achievement_time;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '子STEPの目安達成時間の合計が親STEPの目安達成時間を超えています。';
    }
}
