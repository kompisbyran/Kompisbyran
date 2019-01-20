<?php

namespace AppBundle\Form\Model;

use AppBundle\Entity\City;
use AppBundle\Entity\Municipality;

class SearchConnection
{
    /**
     * @var string|null
     */
    private $q;

    /**
     * @var City|null
     */
    private $city;

    /**
     * @var Municipality|null
     */
    private $municipality;

    /**
     * @var Municipality|null
     */
    private $learnerHomeMunicipality;

    /**
     * @var \DateTime|null
     */
    private $from;

    /**
     * @var \DateTime|null
     */
    private $to;

    /**
     * @var bool
     */
    private $onlyNewlyArrived = false;

    /**
     * @var string|null
     */
    private $meetingStatus;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @return null|string
     */
    public function getQ()
    {
        return $this->q;
    }

    /**
     * @param null|string $q
     */
    public function setQ($q)
    {
        $this->q = trim($q);
    }

    /**
     * @return City|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Municipality|null
     */
    public function getMunicipality()
    {
        return $this->municipality;
    }

    /**
     * @param Municipality|null $municipality
     */
    public function setMunicipality($municipality)
    {
        $this->municipality = $municipality;
    }

    /**
     * @return Municipality|null
     */
    public function getLearnerHomeMunicipality()
    {
        return $this->learnerHomeMunicipality;
    }

    /**
     * @param Municipality|null $learnerHomeMunicipality
     */
    public function setLearnerHomeMunicipality($learnerHomeMunicipality)
    {
        $this->learnerHomeMunicipality = $learnerHomeMunicipality;
    }

    /**
     * @return \DateTime|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param \DateTime|null $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return \DateTime|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param \DateTime|null $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return bool
     */
    public function isOnlyNewlyArrived()
    {
        return $this->onlyNewlyArrived;
    }

    /**
     * @param bool $onlyNewlyArrived
     */
    public function setOnlyNewlyArrived($onlyNewlyArrived)
    {
        $this->onlyNewlyArrived = $onlyNewlyArrived;
    }

    /**
     * @return null|string
     */
    public function getMeetingStatus()
    {
        return $this->meetingStatus;
    }

    /**
     * @param null|string $meetingStatus
     */
    public function setMeetingStatus($meetingStatus)
    {
        $this->meetingStatus = $meetingStatus;
    }

    /**
     * @return null|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param null|string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
