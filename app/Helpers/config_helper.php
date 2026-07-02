<?php

use App\Models\User\FileModel;

if (! function_exists('getCacheKeyVersion')) {
    function getCacheKeyVersion(string $domain = 'common'): int
    {
        // env 우선순위: 도메인별 > 공통 > 기본값(1)
        $domainVersion = (int) env('cache.'.$domain.'Version', 0);
        if ($domainVersion > 0) {
            return $domainVersion;
        }

        $commonVersion = (int) env('cache.keyVersion', 1);
        if ($commonVersion < 1) {
            $commonVersion = 1;
        }

        return $commonVersion;
    }
}

if (! function_exists('getConfigInfoCacheKey')) {
    function getConfigInfoCacheKey(): string
    {
        $version = getCacheKeyVersion('config');

        return 'app_config_info_v'.$version;
    }
}

if (! array_key_exists('app_config_info_memory_cache', $GLOBALS)) {
    $GLOBALS['app_config_info_memory_cache'] = array();
}

if (! array_key_exists('app_language_memory_cache', $GLOBALS)) {
    $GLOBALS['app_language_memory_cache'] = array();
}

if (! function_exists('resolveConfigLanguageCodeFromRequest')) {
    function resolveConfigLanguageCodeFromRequest(): string
    {
        $request = service('request');
        $firstSegment = $request->getUri()->getSegment(1) ?? '';

        $validLocales = getLanguageLocaleCodesCached(true);
        if (in_array($firstSegment, $validLocales, true)) {
            return $firstSegment;
        }

        $app = config('App');
        $defaultLocale = $app->defaultLocale ?? 'kr';
        if (in_array($defaultLocale, $validLocales, true)) {
            return $defaultLocale;
        }

        if (! empty($validLocales)) {
            return $validLocales[0];
        }

        return 'kr';
    }
}

if (! function_exists('getConfigLanguageFields')) {
    function getConfigLanguageFields(): array
    {
        return array(
            'title',
            'description',
            'phone',
            'fax',
            'email',
            'work_hour',
            'post_code',
            'addr1',
            'addr2',
            'biz_no',
        );
    }
}

if (! function_exists('getConfigInfoCached')) {
    function getConfigInfoCached(bool $forceRefresh = false, ?string $languageCode = null): ?object
    {
        $resolvedLanguageCode = is_string($languageCode) && $languageCode !== '' ? $languageCode : resolveConfigLanguageCodeFromRequest();
        $memoryCacheKey = 'config_info_' . $resolvedLanguageCode;
        $languageFields = getConfigLanguageFields();
        $memoryConfigInfo = $GLOBALS['app_config_info_memory_cache'];

        if ($forceRefresh === false && array_key_exists($memoryCacheKey, $memoryConfigInfo) && is_object($memoryConfigInfo[$memoryCacheKey])) {
            $memoryInfo = clone $memoryConfigInfo[$memoryCacheKey];

            $isComplete = true;
            foreach ($languageFields as $field) {
                if (! property_exists($memoryInfo, $field)) {
                    $isComplete = false;
                    break;
                }
            }

            if ($isComplete === true) {
                return $memoryInfo;
            }

            // 구형 캐시 객체(스키마 전환 이전)를 감지하면 강제로 재조회한다.
            $forceRefresh = true;
        }

        $cache = \Config\Services::cache();
        $cacheKey = getConfigInfoCacheKey() . '_' . $resolvedLanguageCode;

        if ($forceRefresh === false) {
            $cached = $cache->get($cacheKey);
            if (is_object($cached)) {
                $isComplete = true;
                foreach ($languageFields as $field) {
                    if (! property_exists($cached, $field)) {
                        $isComplete = false;
                        break;
                    }
                }

                if ($isComplete === false) {
                    // 구형 캐시 객체는 재생성해서 저장한다.
                    $forceRefresh = true;
                }

                if ($forceRefresh === false) {
                    $memoryConfigInfo[$memoryCacheKey] = clone $cached;
                    $GLOBALS['app_config_info_memory_cache'] = $memoryConfigInfo;

                    return clone $memoryConfigInfo[$memoryCacheKey];
                }
            }
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('config');
            $info = $builder->get()->getRow();

            if (! is_object($info)) {
                $info = new \stdClass();
            }

            $needsLanguageHydration = (($info->language_yn ?? 'N') === 'Y');
            if ($needsLanguageHydration === false) {
                foreach ($languageFields as $field) {
                    if (! property_exists($info, $field)) {
                        $needsLanguageHydration = true;
                        break;
                    }
                }
            }

            if ($needsLanguageHydration === true) {
                try {
                    $languageBuilder = $db->table('config_language');
                    $languageBuilder->where('language_code', $resolvedLanguageCode);
                    $languageInfo = $languageBuilder->get()->getRow();

                    if (! is_object($languageInfo)) {
                        // 다국어 OFF 상태나 코드 미일치 시 첫 번째 사용 언어를 fallback으로 사용
                        $fallbackBuilder = $db->table('config_language');
                        $fallbackBuilder->where('use_yn', 'Y');
                        $fallbackBuilder->orderBy('language_idx', 'asc');
                        $languageInfo = $fallbackBuilder->get()->getRow();
                    }

                    if (! is_object($languageInfo)) {
                        $fallbackBuilder = $db->table('config_language');
                        $fallbackBuilder->orderBy('language_idx', 'asc');
                        $languageInfo = $fallbackBuilder->get()->getRow();
                    }

                    if (is_object($languageInfo)) {
                        foreach ($languageFields as $field) {
                            if (property_exists($languageInfo, $field) && $languageInfo->{$field} !== null) {
                                $info->{$field} = $languageInfo->{$field};
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    // 스키마 반영 전/중에는 공통 설정만 반환한다.
                }
            }

            foreach ($languageFields as $field) {
                if (! property_exists($info, $field) || $info->{$field} === null) {
                    $info->{$field} = '';
                }
            }

            if (! property_exists($info, 'language_yn') || $info->language_yn === null) {
                $info->language_yn = 'N';
            }

            if (! property_exists($info, 'company_logo')) {
                $info->company_logo = null;
            }

            if (! property_exists($info, 'program_ver') || $info->program_ver === null) {
                $info->program_ver = '';
            }

            $info->company_logo_info = null;

            if (! empty($info->company_logo)) {
                $fileModel = new FileModel();
                $info->company_logo_info = $fileModel->getFileInfo($info->company_logo);
            }

            // 설정 변경 시 즉시 삭제하므로 TTL은 길게 유지한다.
            $cache->save($cacheKey, $info, 31536000);
            $memoryConfigInfo[$memoryCacheKey] = is_object($info) ? clone $info : null;
            $GLOBALS['app_config_info_memory_cache'] = $memoryConfigInfo;

            return is_object($memoryConfigInfo[$memoryCacheKey] ?? null) ? clone $memoryConfigInfo[$memoryCacheKey] : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (! function_exists('clearConfigInfoCache')) {
    function clearConfigInfoCache(): void
    {
        $GLOBALS['app_config_info_memory_cache'] = array();

        $cache = \Config\Services::cache();
        $prefix = getConfigInfoCacheKey() . '_';
        $localeCodes = getLanguageLocaleCodesCached(false);

        foreach ($localeCodes as $localeCode) {
            $cache->delete($prefix . $localeCode);
        }

        // 언어 테이블이 비어있을 때 기본 키도 함께 제거
        $cache->delete($prefix . 'kr');
    }
}

if (! function_exists('getLanguageListCacheKey')) {
    function getLanguageListCacheKey(bool $onlyUse = true): string
    {
        $version = getCacheKeyVersion('language');

        return $onlyUse ? 'app_language_list_use_v'.$version : 'app_language_list_all_v'.$version;
    }
}

if (! function_exists('getLanguageListCached')) {
    function getLanguageListCached(bool $onlyUse = true, bool $forceRefresh = false): array
    {
        $cacheKey = getLanguageListCacheKey($onlyUse);
        $memoryCache = $GLOBALS['app_language_memory_cache'];

        if ($forceRefresh === false && array_key_exists($cacheKey, $memoryCache)) {
            return $memoryCache[$cacheKey];
        }

        $cache = \Config\Services::cache();

        if ($forceRefresh === false) {
            $cached = $cache->get($cacheKey);
            if (is_array($cached)) {
                $GLOBALS['app_language_memory_cache'][$cacheKey] = $cached;

                return $cached;
            }
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('config_language');
            $builder->orderBy('language_idx', 'asc');

            if ($onlyUse === true) {
                $builder->where('use_yn', 'Y');
            }

            $list = $builder->get()->getResult();
            if (! is_array($list)) {
                $list = array();
            }

            $cache->save($cacheKey, $list, 31536000);
            $GLOBALS['app_language_memory_cache'][$cacheKey] = $list;

            return $list;
        } catch (\Throwable $e) {
            return array();
        }
    }
}

if (! function_exists('getLanguageLocaleCodesCached')) {
    function getLanguageLocaleCodesCached(bool $onlyUse = true, bool $forceRefresh = false): array
    {
        $list = getLanguageListCached($onlyUse, $forceRefresh);
        $codes = array();

        foreach ($list as $row) {
            $code = $row->language_code ?? null;
            if (is_string($code) && $code !== '') {
                $codes[] = $code;
            }
        }

        return $codes;
    }
}

if (! function_exists('clearLanguageCache')) {
    function clearLanguageCache(): void
    {
        $cache = \Config\Services::cache();
        $cache->delete(getLanguageListCacheKey(true));
        $cache->delete(getLanguageListCacheKey(false));
        $GLOBALS['app_language_memory_cache'] = array();
    }
}
