<?php

namespace AppBundle\Manager;

/**
 * Interface CategoryInterface
 * @package AppBundle\Manager
 */
interface CategoryInterface
{
    /**
     * @param $locale
     * @return mixed
     */
    public function getFindAllMusicByLocale($locale);

    /**
     * @param $locale
     * @return mixed
     */
    public function getFindAllGeneralByLocale($locale);

    /**
     * @param $locale
     * @return mixed
     */
    public function getFindAllByLocale($locale);

    /**
     * @param $id
     * @param $locale
     * @return mixed
     */
    public function getFindOneByIdAndLocale($id, $locale);

    /**
     * @param array $ids
     * @param $locale
     * @return mixed
     */
    public function getFindByIdsAndLocale(array $ids, $locale);
}