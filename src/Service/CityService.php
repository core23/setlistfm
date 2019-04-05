<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SetlistFm\Service;

use Core23\SetlistFm\Builder\CitySearchBuilder;
use Core23\SetlistFm\Connection\ConnectionInterface;
use Core23\SetlistFm\Exception\ApiException;
use Core23\SetlistFm\Exception\NotFoundException;
use Core23\SetlistFm\Model\City;

final class CityService
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get the city data for an id.
     *
     * @param int $cityId
     *
     * @throws ApiException
     * @throws NotFoundException
     *
     * @return City
     */
    public function getCity(int $cityId): City
    {
        return City::fromApi(
            $this->connection->call('city/'.$cityId)
        );
    }

    /**
     * Search for cities.
     *
     * @param CitySearchBuilder $builder
     *
     * @return City[]
     */
    public function search(CitySearchBuilder $builder): array
    {
        $response = $this->connection->call('search/cities', $builder->getQuery());

        if (!\array_key_exists('cities', $response)) {
            return [];
        }

        return array_map(static function ($data) {
            return City::fromApi($data);
        }, $response['cities']);
    }
}
