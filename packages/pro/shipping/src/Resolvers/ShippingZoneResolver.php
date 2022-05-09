<?php

namespace GetCandy\Shipping\Resolvers;

use GetCandy\Models\Country;
use GetCandy\Models\State;
use GetCandy\Shipping\DataTransferObjects\PostcodeLookup;
use GetCandy\Shipping\Models\ShippingZone;
use Illuminate\Support\Collection;

class ShippingZoneResolver
{
    /**
     * The country to use when resolving zones.
     *
     * @var Country
     */
    protected ?Country $country = null;

    /**
     * The state to use when resolving zones.
     *
     * @var State
     */
    protected ?State $state = null;

    /**
     * The postcode lookup to use when resolving zones.
     *
     * @var PostcodeLookup
     */
    protected ?PostcodeLookup $postcodeLookup = null;

    /**
     * The type of zones we want to query.
     *
     * @var Collection
     */
    protected Collection $types;

    /**
     * Initialise the resolver.
     */
    public function __construct()
    {
        $this->types = collect();
    }

    /**
     * Set the country.
     *
     * @param  Country  $country
     * @return self
     */
    public function country(Country $country = null): self
    {
        $this->country = $country;
        $this->types->push('countries');

        return $this;
    }

    /**
     * Set the state.
     *
     * @param  State  $state
     * @return self
     */
    public function state(State $state = null): self
    {
        $this->state = $state;
        $this->types->push('states');

        return $this;
    }

    /**
     * Set the postcode to use when resolving.
     *
     * @param  string  $postcode
     * @return self
     */
    public function postcode(PostcodeLookup $postcodeLookup): self
    {
        $this->postcodeLookup = $postcodeLookup;
        $this->types->push('postcodes');

        return $this;
    }

    /**
     * Return the shipping zones based on the criteria.
     *
     * @return Collection
     */
    public function get()
    {
        $query = ShippingZone::query();

        $query->where(function ($builder) {
            if ($this->country) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->country->id);
                    })->whereType('countries');
                });
            }

            if ($this->state) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('states', function ($query) {
                        $query->where('state_id', $this->state->id);
                    })->whereType('states');
                });
            }

            if ($this->postcodeLookup) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('postcodes', function ($query) {
                        $postcodeParts = (new PostcodeResolver)->getParts(
                            $this->postcodeLookup->postcode
                        );
                        $query->whereIn('postcode', $postcodeParts);
                    })->where(function ($qb) {
                        $qb->whereHas('countries', function ($query) {
                            $query->where('country_id', $this->postcodeLookup->country->id);
                        })->whereType('postcodes');
                    });
                })->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->postcodeLookup->country->id);
                    })->whereType('countries');
                });
            }
        });

        return $query->get();
    }
}