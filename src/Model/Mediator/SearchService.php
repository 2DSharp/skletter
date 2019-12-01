<?php
/*
 * This file is part of Skletter <https://github.com/2DSharp/Skletter>.
 *
 * (c) Dedipyaman Das <2d@twodee.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Skletter\Model\Mediator;


use Skletter\Model\RemoteService\DTO\UserDTO;
use Skletter\Model\RemoteService\Search\DTO\SearchProfile;
use Skletter\Model\RemoteService\Search\SearchClient;
use Skletter\Model\ValueObject\ProfileSuggestion;

class SearchService
{
    /**
     * @var SearchClient
     */
    private SearchClient $search;

    public function __construct(SearchClient $search)
    {
        $this->search = $search;
    }

    public function initiateIndexing(UserDTO $dto)
    {
        $profile = new SearchProfile();
        $profile->indexId = $dto->id;
        $profile->name = $dto->name;
        $profile->username = $dto->username;
        $profile->picture = "";

        $this->search->registerIndex($profile);
    }

    /**
     * Return an array of suggestions wrapped in VOs
     * @param string $query
     * @return array
     */
    public function suggest(string $query): array
    {
        $results = $this->search->suggest($query);
        $suggestions = [];
        $i = 0;
        foreach ($results as $result) {
            $profile = new ProfileSuggestion($result->name, $result->username);
            $suggestions[$i++] = $profile;
        }
        return $suggestions;
    }
}