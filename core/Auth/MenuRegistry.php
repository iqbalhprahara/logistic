<?php

namespace Core\Auth;

use Core\Auth\Models\Menu;
use Core\Auth\Models\User;
use Illuminate\Cache\CacheManager;
use Illuminate\Cache\Repository;
use Illuminate\Support\Collection;

class MenuRegistry
{
    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \DateInterval|int */
    public static $cacheExpirationTime;

    /** @var string */
    public static $cacheKey;

    /** @var \Illuminate\Support\Collection */
    protected $menus;

    public function __construct(
        protected CacheManager $cacheManager
    ) {
        $this->initializeCache();
    }

    protected function initializeCache()
    {
        self::$cacheExpirationTime = config('core.auth-menu.cache.expiration_time') ?: \DateInterval::createFromDateString('24 hours');

        self::$cacheKey = config('core.auth-menu.cache.key');

        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        // the 'default' fallback here is from the menu.php config file,
        // where 'default' means to use config(cache.default)
        $cacheDriver = config('core.auth-menu.cache.store', 'default');

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (! \array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    /**
     * Get the menus based on the passed params.
     */
    public function getMenusByParams(array $params = [], $onlyOne = false): Collection
    {
        $this->loadMenus();

        $method = $onlyOne ? 'firsWhere' : 'filter';
        $menus = $this->menus->$method(function ($item) use ($params) {
            foreach ($params as $attr => $value) {
                if ($item[$attr] !== $value) {
                    return false;
                }
            }

            return true;
        });

        if ($onlyOne) {
            $menus = new Collection($menus ? [$menus] : []);
        }

        return $menus;
    }

    protected function loadMenus()
    {
        if ($this->menus) {
            return;
        }

        $this->menus = collect($this->cache->remember(self::$cacheKey, self::$cacheExpirationTime, function () {
            return $this->getSerializedMenusForCache();
        }));
    }

    /*
     * Make the cache smaller using an array with only required fields
     */
    private function getSerializedMenusForCache()
    {
        $menus = $this->getMenusMap();
        $guards = $menus->pluck('menu.guard_name')->unique();

        $serializedMenu = [];
        foreach ($this->getUsers() as $user) {
            foreach ($guards as $guard) {
                $serializedMenu[] = [
                    'user_uuid' => $user->uuid,
                    'guard' => $guard,
                    'menus' => $this->mapUserMenus($user, $menus, $guard),
                ];
            }
        }

        return $serializedMenu;
    }

    protected function mapUserMenus(User $user, Collection $menus, $guard)
    {
        return $menus->filter(function ($item) use ($user, $guard) {
            if ($item['menu']->guard_name !== $guard) {
                return false;
            }

            return $item['menu']->gate === null || $user->can($item['menu']->gate);
        })->map(function ($item) use ($user, $guard) {
            return [
                'id' => $item['menu']->id,
                'name' => $item['menu']->name,
                'icon' => $item['menu']->icon,
                'type' => $item['menu']->type,
                'url' => $item['menu']->url,
                'submenus' => $this->mapUserMenus($user, $item['submenus'], $guard),
            ];
        })->all();
    }

    /**
     * Flush the cache.
     */
    public function forgetCachedMenus()
    {
        $this->menus = null;

        return $this->cache->forget(self::$cacheKey);
    }

    protected function getUsers(): Collection
    {
        return User::all();
    }

    protected function getMenusMap(): Collection
    {
        $menus = $this->getMenus();
        return $this->menuMapByParam($menus, ['parent_uuid' => null]);
    }

    protected function menuMapByParam(Collection $allMenu, $params = []): Collection
    {
        return $allMenu->filter(function (Menu $item) use ($params) {
            foreach ($params as $attr => $value) {
                if ($item->getAttribute($attr) !== $value) {
                    return false;
                }
            }

            return true;
        })
        ->sortBy('sort')
        ->map(function (Menu $item) use ($allMenu) {
            return [
                'menu' => $item,
                'submenus' => $this->menuMapByParam($allMenu, ['parent_uuid' => $item->uuid]),
            ];
        })->values();
    }

    protected function getMenus(): Collection
    {
        return Menu::all();
    }
}
