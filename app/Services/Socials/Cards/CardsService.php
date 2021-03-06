<?php

namespace App\Services\Socials\Cards;

use App\Models\Auth\User;
use App\Models\Social\Cards;
use App\Services\BaseService;
use App\Jobs\Social\MediaCards\PlurkPrimaryDestory;
use App\Jobs\Social\MediaCards\PlurkPrimaryPublish;
use App\Jobs\Social\MediaCards\TwitterPrimaryPublish;
use App\Jobs\Social\MediaCards\TwitterPrimaryDestory;
use App\Jobs\Social\MediaCards\FacebookPrimaryDestory;
use App\Jobs\Social\MediaCards\FacebookPrimaryPublish;
use App\Jobs\Social\MediaCards\FacebookSecondaryDestory;
use App\Jobs\Social\MediaCards\FacebookSecondaryPublish;
use App\Repositories\Backend\Social\MediaCardsRepository;

/**
 * Class CardsService.
 */
class CardsService extends BaseService implements CardsContract
{
    /**
     * @var MediaCardsRepository
     */
    protected $mediaCardsRepository;

    /**
     * FacebookPrimaryService constructor.
     */
    public function __construct(MediaCardsRepository $mediaCardsRepository)
    {
        $this->mediaCardsRepository = $mediaCardsRepository;
    }

    /**
     * @param Cards $cards
     * @return void
     */
    public function publish(Cards $cards)
    {
        if (env('FACEBOOK_PRIMARY_CREATE_POST', false) && (! $this->mediaCardsRepository->findByCardId($cards->id, 'facebook', 'primary')))
            FacebookPrimaryPublish::dispatch($cards);
        if (env('FACEBOOK_SECONDARY_CREATE_POST', false) && (! $this->mediaCardsRepository->findByCardId($cards->id, 'facebook', 'secondary')))
            FacebookSecondaryPublish::dispatch($cards);
        if (env('TWITTER_CREATE_POST', false) && (! $this->mediaCardsRepository->findByCardId($cards->id, 'twitter', 'primary')))
            TwitterPrimaryPublish::dispatch($cards);
        if (env('PLURK_CREATE_POST', false) && (! $this->mediaCardsRepository->findByCardId($cards->id, 'plurk', 'primary')))
            PlurkPrimaryPublish::dispatch($cards);
    }

    /**
     * @param User  $user
     * @param Cards $cards
     * @param array $options
     * @return void
     */
    public function destory(User $user, Cards $cards, array $options)
    {
        if ($this->mediaCardsRepository->findByCardId($cards->id, 'facebook', 'primary'))
            FacebookPrimaryDestory::dispatch($user, $cards, $options);

        if ($this->mediaCardsRepository->findByCardId($cards->id, 'facebook', 'secondary'))
            FacebookSecondaryDestory::dispatch($user, $cards, $options);

        if ($this->mediaCardsRepository->findByCardId($cards->id, 'twitter', 'primary'))
            TwitterPrimaryDestory::dispatch($user, $cards, $options);

        if ($this->mediaCardsRepository->findByCardId($cards->id, 'plurk', 'primary'))
            PlurkPrimaryDestory::dispatch($user, $cards, $options);
    }
}
