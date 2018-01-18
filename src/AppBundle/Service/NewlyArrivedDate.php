<?php

namespace AppBundle\Service;

class NewlyArrivedDate
{
    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @param \DateTime|null $date
     */
    public function __construct(\DateTime $date = null)
    {
        if (!$date) {
            $date = new \DateTime();
            $date->modify('-3 year');
            $date->modify('-1 month');
        }

        $this->date = \DateTimeImmutable::createFromMutable($date);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }
}
