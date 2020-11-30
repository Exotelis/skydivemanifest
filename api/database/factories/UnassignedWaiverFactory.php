<?php

namespace Database\Factories;

use App\Models\UnassignedWaiver;
use App\Models\Waiver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class UnassignedWaiverFactory
 * @package Database\Factories
 *
 * @method Factory forWaiver()
 */
class UnassignedWaiverFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UnassignedWaiver::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email'     => $this->faker->freeEmail,
            'firstname' => $this->faker->firstName,
            'ip'        => $this->faker->randomElement([$this->faker->ipv4, $this->faker->ipv6]),
            'lastname'  => $this->faker->lastName,
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAFCAYAAABM6GxJAAAAeklEQVQoU53OsQnCYBT' .
                'E8d9XOYELSOo0kjIoom7gCg6U2iqtnRNkiQjOYGuvPEjEfJDGgyse7+7PJVOVOGCFAhf0uGe575lwwg5rVDPBJzpcB08A759ShF5' .
                'ohwULbLDNwJFr8IgFZ9S45fSsFEvDAVuOvwD8owAdsf8AGfMRDpjy0ScAAAAASUVORK5CYII=',
            'waiver_id' => Waiver::factory(),
        ];
    }
}
